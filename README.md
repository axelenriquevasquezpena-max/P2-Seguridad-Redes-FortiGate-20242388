**Video demostrativo: PENDIENTE — insertar aquí el enlace real de YouTube o OneDrive institucional (máximo 10 minutos).**

# Práctica P2 — Seguridad de redes con FortiGate

**Estudiante:** Axel Enrique Vasquez Peña  
**Matrícula:** 2024-2388  
**Fecha de documentación:** 24 de septiembre de 2026  
**Entorno:** GNS3, VMware Workstation, FortiGate-VM KVM 7.0.9, IOSvL2, Ubuntu Server y Kali Linux.

## Propósito

Implementar una red segmentada para usuarios, servidor web y base de datos; controlar los accesos entre segmentos; publicar un catálogo por HTTPS y demostrar protección ante patrones SQL Injection, descargas de ejecutables y tráfico SYN de tasa elevada.

La documentación se basa en las configuraciones, salidas y capturas aportadas por el estudiante. No es una auditoría independiente de los equipos. Las exportaciones originales todavía deben incorporarse tras retirar secretos.

## Resultado y alcance

El catálogo HTTPS funciona y se observaron registros de denegación de usuarios a MariaDB, detección SQLi, cuarentena temporal, bloqueo de ejecutables y protección DoS. Application Control identificó Firefox en Monitor.

**Limitación principal:** IPS, cuarentena, File Filter y Application Control se probaron por **HTTP/80** mediante una política temporal. No se ha demostrado inspección profunda del contenido **HTTPS/443**. La importación de un certificado RSA de 2048 bits fue rechazada y se solicitó una evaluación completa a Fortinet. No se afirma cumplimiento total ni aceptación académica de la alternativa HTTP.

## Topología

![Topología P2](topologia-gns3.png)

Cloud1 conecta Windows a la administración por VMnet1. Cloud2 conecta la WAN a la red doméstica. FortiGate enruta entre VLAN por subinterfaces de port2. Los iconos de PC de las VM no cambian su función de servidores.

| Segmento | VLAN | Red / máscara | Gateway | Equipo |
|---|---:|---|---|---|
| Usuarios | 10 | 10.23.88.0/25 — 255.255.255.128 | 10.23.88.1 | Kali: 10.23.88.21 por DHCP |
| WEB | 20 | 10.23.88.128/28 — 255.255.255.240 | 10.23.88.129 | WEB-P2: 10.23.88.130 |
| DB | 30 | 10.23.88.144/28 — 255.255.255.240 | 10.23.88.145 | DB-P2: 10.23.88.146 |
| Administración | — | 192.168.6.0/24 | — | Windows .1; FortiGate port1 .131 |
| WAN doméstica | — | 192.168.1.0/24 | 192.168.1.1 | FortiGate port3 .16 por DHCP |

El esquema 10.23.88.x fue elegido a partir de la matrícula 2024-2388. Se usan dos subredes /28, una por servidor, para que su comunicación atraviese el firewall. Verificar las IP DHCP tras reiniciar.

## Políticas relevantes

| Política | Flujo | Servicio | Acción / NAT |
|---|---|---|---|
| USUARIOS_A_WEB_HTTPS, ID 5 | Usuarios → SRV_WEB | TCP/443 | ACCEPT; sin NAT; no-inspection |
| BLOQUEO_USUARIOS_DB_3306, ID 6 | Usuarios → SRV_DB | TCP/3306 | DENY y registro |
| WEB_TO_DB_3306 | SRV_WEB → SRV_DB | TCP/3306 | ACCEPT; sin NAT |
| ADMIN_SSH_WEB / ADMIN_SSH_DB | PC_ADMIN → servidor correspondiente | TCP/22 | Administración; sin NAT |
| LAB_USUARIOS_WEB_HTTP, ID 7 | Usuarios → SRV_WEB | TCP/80 | ACCEPT; IPS, File Filter y Application Control |
| TEMP_WEB_UPDATES | WEB → WAN | DNS, HTTP, HTTPS | NAT de salida; usada para instalación |

La ruta por defecto utiliza la WAN y 192.168.1.1, distancia 5 según la configuración reportada. Falta incorporar evidencia de ruta y traducción efectiva.

**Revisión final de mínimo privilegio:** verificar que no existen reglas amplias para WEB → DB en otros puertos ni WEB → otros destinos. La excepción TEMP_WEB_UPDATES debe desactivarse cuando deje de ser necesaria. No se cuenta aún con una prueba negativa completa de esos flujos.

## Matriz de cumplimiento

| Requisito | Estado documentado |
|---|---|
| VLAN, usuarios DHCP /25 y servidores /28 | Configurados; agregar capturas finales y exportaciones |
| Seguridad básica del switch | Sticky, restrict, PortFast, BPDU Guard y puertos apagados reportados; falta running-config |
| WEB HTTPS y Usuarios/443 | Catálogo y conectividad observados |
| Usuarios → DB/3306 bloqueado | Log de denegación ID 6 |
| WEB → DB/3306 | Catálogo y consulta MariaDB reportada exitosa |
| WEB limitado a los flujos exigidos | Falta revisión de excepciones y prueba negativa |
| Ruta y NAT | Configurados para WAN; falta evidencia final |
| DPI sobre contenido HTTPS | **No demostrado; pendiente** |
| SQLi y cuarentena | Demostrados por HTTP |
| Bloqueo .exe | Demostrado por HTTP con File Filter |
| Application Control | Firefox identificado en Monitor; no bloquea .exe en la evidencia |
| Protección DoS | tcp_syn_flood, clear_session hacia WEB:443 |
| GitHub, video y TXT | Repositorio público creado; video y enlace final de entrega pendientes |

## Evidencias seleccionadas

### Segmentación
![Denegación a DB y acceso HTTPS](01-politicas-trafico.png)

### IPS por HTTP
![SQLi bloqueado](02-ips-sqli.png)

### Cuarentena y recuperación
![Bloqueo temporal](03-cuarentena.png)
![Catálogo después de la cuarentena](04-recuperacion.png)

### File Filter
![Ejecutable bloqueado](05-file-filter.png)

### Application Control
![Firefox en Monitor](06-application-control.png)

### Protección DoS
![Evento SYN flood](07-dos.png)

## Documentación

- [Implementación](implementacion.md).
- [Pruebas y resultados](pruebas.md).
- [Limitación HTTPS](limitaciones.md).
- [Inventario de evidencias](evidencias.md).
- [Guion de video](video.md).
- [Preparación de GitHub](primeros-pasos.md).
- [Condiciones de entrega](condiciones-entrega.md).
- [Configuraciones por incorporar](configuraciones-pendientes.md).
- [Comandos de pruebas](pruebas-manuales.md).

## Conclusión

Las pruebas documentan controles efectivos en los flujos ensayados. La protección del contenido HTTPS permanece sin validar y los resultados HTTP se presentan como evidencia parcial. Antes de entregar deben completarse las exportaciones sanitizadas, los enlaces y las verificaciones señaladas.

## Informe y guion descargables

- [Informe Word](AxelEnriqueVasquezPeña_20242388_P2_Informe.docx)
- [Informe PDF](AxelEnriqueVasquezPeña_20242388_P2_Informe.pdf)
- [Guion Word](AxelEnriqueVasquezPeña_20242388_P2_Guion.docx)
- [Guion PDF](AxelEnriqueVasquezPeña_20242388_P2_Guion.pdf)


