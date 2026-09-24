# Configuración implementada

## Plataforma y conexiones

GNS3 2.2.61 y VMware Workstation 17.6.3. FortiGate-VM KVM 7.0.9 activo, un vCPU y RAM aumentada a 2 GiB tras errores de memoria. El aumento no corrigió el rechazo del certificado.

Toda configuración y demostración del FortiGate se realiza por GUI. Los comandos Linux y del switch se ejecutan en sus respectivos equipos.

| Interfaz | Conexión |
|---|---|
| port1 | Cloud1 / VMnet1, administración 192.168.6.131 |
| port2 | Trunk a Gi0/0 del switch |
| port3, WAN | Cloud2, DHCP 192.168.1.16 y gateway 192.168.1.1 |

Se reportó desactivar la obtención de gateway predeterminado por port1 y utilizar WAN. Confirmar en la exportación final.

## Switch SW-P2-2024-2388

| Puerto | Función | VLAN | MAC sticky reportada |
|---|---|---|---|
| Gi0/0 | Trunk a FortiGate | 10,20,30; nativa 999 | No corresponde |
| Gi0/1 | Kali | 10 | 000c.2927.4516 |
| Gi0/2 | WEB-P2 | 20 | 000c.29cf.a109 |
| Gi0/3 | DB-P2 | 30 | 000c.29a7.54de |

Puertos de hosts access, sin negociación, port security máximo una MAC, aprendizaje sticky, violación restrict, PortFast edge y BPDU Guard. Se reportaron Gi1/0 a Gi3/3 en VLAN 999 y apagados. Se corrigieron las MAC de adaptadores virtuales aprendidas inicialmente.

Cotejar con show running-config y guardar mediante copy running-config startup-config. No se presenta una reconstrucción como exportación real. No se afirma haber configurado DHCP snooping, DAI ni storm control.

## VLAN y DHCP

- USUARIOS_V10: VLAN 10 sobre port2, 10.23.88.1/25.
- WEB_V20: VLAN 20 sobre port2, 10.23.88.129/28.
- DB_V30: VLAN 30 sobre port2, 10.23.88.145/28.

DHCP: 10.23.88.20–10.23.88.126, gateway 10.23.88.1 y DNS reportado 192.168.1.1. Kali recibió 10.23.88.21. Se habilitó PING en las interfaces de VLAN según lo configurado.

## Objetos y excepciones

SRV_WEB = 10.23.88.130/32; SRV_DB = 10.23.88.146/32; PC_ADMIN = 192.168.6.1/32. El objeto de usuarios cubre la red /25. Las políticas están en el README.

LAB_USUARIOS_WEB_HTTP es una excepción de pruebas. Mientras esté activa no se afirma acceso exclusivamente HTTPS. Si se exige cierre por 443 solamente, debe desactivarse; si se mantiene para demostrar pruebas parciales, declararlo.

TEMP_WEB_UPDATES permite paquetes desde WEB por WAN con NAT. No afirmar que WEB solo inicia tráfico a DB mientras esté activa. Conservar evidencia del NAT antes de desactivarla y revisar otras reglas amplias.

## WEB-P2

Ubuntu Server 24.04, hostname webp2, ens33, 10.23.88.130/28, gateway 10.23.88.129. Nginx 1.24 y PHP-FPM 8.3.

| Archivo | Función |
|---|---|
| /etc/netplan/50-cloud-init.yaml | Red estática |
| /etc/nginx/sites-available/p2 | Sitio HTTPS |
| /etc/nginx/sites-available/p2-http-lab | Sitio HTTP temporal |
| /var/www/p2/index.php | Catálogo |
| /etc/p2/database.php | Credenciales; publicar solo plantilla sanitizada |
| /etc/nginx/ssl/web-p2.crt | Certificado público |
| /etc/nginx/ssl/web-p2.key | Clave privada; no publicar |
| /var/www/p2/downloads/control.txt | Archivo de control |
| /var/www/p2/downloads/putty.exe | Ejecutable legítimo de prueba |

Raíz /var/www/p2, socket /run/php/php8.3-fpm.sock, TCP/443, TLS 1.2/1.3 según lo configurado. Certificado autofirmado RSA 2048, CN web-p2.test, SAN DNS web-p2.test e IP 10.23.88.130. Una página accesible no prueba confianza de CA pública ni inspección SSL del firewall.

PDO ejecuta una consulta fija: SELECT id, nombre, precio FROM productos ORDER BY id. El HTML escapa datos y muestra UTC. No recibe parámetros SQL del cliente. La prueba IPS detectó un patrón de cabecera; no demostró una aplicación explotable.

Se validó sintaxis PHP y curl devolvió el catálogo por HTTPS con confianza explícita en el certificado. Los secretos están fuera de la raíz pública, con permisos restringidos.

## DB-P2

Ubuntu Server, hostname dbp2, ens33, 10.23.88.146/28, gateway 10.23.88.145; MariaDB 10.11.

Configuración reportada de /etc/mysql/mariadb.conf.d/60-p2.cnf:

    [mysqld]
    bind-address = 10.23.88.146
    skip-name-resolve

Base laboratorio_p2, tabla productos. Cuenta webapp restringida a 10.23.88.130 con SELECT. Se omite la contraseña.

| ID | Producto | Precio |
|---|---|---|
| 1 | Teclado | 850.00 |
| 2 | Mouse | 450.00 |
| 3 | Monitor | 7500.00 |

## Perfiles de seguridad

**IPS_SQLI_LAB:** HTTP.Header.SQL.Injection con registro. Se observó dropped y después cuarentena del origen durante cinco minutos. Política LAB_USUARIOS_WEB_HTTP.

**BLOQUEO_EXE_LAB:** Flow-based; regla Bloquear_EXE_HTTP; protocolo HTTP; tráfico Both; tipo exe; Block; Password-protected only desactivado. Prueba con ejecutable real.

**APP_CONTROL_LAB:** HTTP.BROWSER_Firefox, ID 34050, Monitor. Evento pass hacia /downloads/control.txt. El bloqueo del ejecutable pertenece a File Filter.

**DOS_USUARIOS_WEB:** interfaz USUARIOS_V10, origen usuarios, destino SRV_WEB, servicio HTTPS; tcp_syn_flood en Block con registro. Umbral de demostración indicado: 50; agregar captura de su valor final. No es recomendación de producción ni límite de solicitudes HTTP por segundo. Log clear_session, destino 443, contador 451.

