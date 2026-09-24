# Pruebas y resultados

Las evidencias son capturas y salidas aportadas por el estudiante. No se atribuyen mediciones no realizadas.

| Prueba | Resultado observado | Archivo |
|---|---|---|
| Usuarios → WEB/443 | Puerto abierto y aceptación ID 5 | 01-politicas-trafico.png; 11-conectividad.png |
| Usuarios → DB/3306 | Timeout y Deny: policy violation, ID 6 | 01-politicas-trafico.png; 11-conectividad.png |
| WEB consulta DB | Catálogo con tres productos y consulta autenticada reportada exitosa | 04-recuperacion.png; agregar salida directa |
| SQLi HTTP | HTTP.Header.SQL.Injection, dropped, .21 → .130:80 | 02-ips-sqli.png |
| Cuarentena | Petición normal recibe bloqueo de FortiGate | 03-cuarentena.png |
| Recuperación | Catálogo después de cinco minutos y refresco | 04-recuperacion.png |
| Ejecutable | Firefox Failed y log blocked, exe, putty.exe | 05-file-filter.png; 08-descarga-fallida.png |
| Control de texto | Evento pass hacia control.txt; agregar contenido visible | 06-application-control.png |
| Application Control | HTTP.BROWSER_Firefox, APP_CONTROL_LAB, pass | 06-application-control.png |
| DoS | tcp_syn_flood, clear_session, .130:443 | 07-dos.png |
| Certificado | OpenSSL lee PKCS#12; FortiGate rechaza tamaño de clave | 09-error-certificado.png |

## Interpretación

- HTTP 200 no basta para probar acceso: el bloqueo FortiGate también respondió 200. Revisar cuerpo y registros.
- Timeout o descarga fallida sin log no prueba bloqueo del firewall.
- pass en Application Control corresponde a Monitor, no a bloqueo.
- La cuarentena se evidenció mediante petición normal bloqueada y recuperación. Agregar lista de cuarentena con caducidad si está disponible.
- El contador 451 no se interpreta como medición independiente de 451 paquetes descartados.
- Los relojes del firewall y la página UTC muestran diferencias. No se verificó sincronización; revisarla para el video.
- No se explotó MariaDB; el IPS detectó un patrón en una cabecera.
- No hay evidencia de estos bloqueos dentro de HTTPS.

## Revisión final

Faltan capturas de VLAN/DHCP; exportaciones reales; evidencia de ruta y NAT efectivo; revisión de TEMP_WEB_UPDATES; pruebas negativas WEB → DB por otro puerto y WEB → otros destinos; captura del texto permitido y umbral DoS final; publicación de GitHub y video. HTTPS profundo permanece pendiente.

