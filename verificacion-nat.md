# Verificación de NAT por GUI

El 24/09/2026 se revisó Log & Report > Forward Traffic en FortiGate. Tras la solicitud HTTP desde WEB, el registro de sesión 266 muestra:

| Campo | Valor |
|---|---|
| Fecha/hora del equipo | 2026/09/24 13:55:56 (UTC-07:00) |
| Origen | 10.23.88.130:47396, WEB_V20 |
| NAT Translation | Source |
| NAT IP | 192.168.1.7 |
| Destino | 172.66.147.243:80 |
| Salida | WAN (port3) |
| Política | TEMP_WEB_UPDATES (3) |
| Acción | Accept: session close |
| Recibido / enviado | 490 B / 395 B |

El registro confirma SNAT efectivo de WEB hacia la WAN. La dirección traducida es privada; no se presenta como IP pública. La excepción temporal estaba activa durante esta prueba. Su desactivación y las pruebas finales de aislamiento todavía deben verificarse.

## Cierre de la excepción

El 24/09/2026 se desactivó TEMP_WEB_UPDATES mediante GUI. Se volvió a abrir la política y se comprobó que Enable this policy estaba desmarcado. Las pruebas de aislamiento posteriores y un nuevo respaldo final están pendientes. El respaldo publicado anteriormente corresponde al estado previo.

## Pruebas posteriores al cierre

Salida aportada desde WEB:

```text
DB:3306 ACCESIBLE
DB:22 SIN CONEXION
DB:80 SIN CONEXION
curl: (28) Failed to connect to 172.66.147.243 port 80 after 4012 ms: Timeout was reached
```

La GUI confirma sesión 1390, 24/09/2026 14:01:26 UTC-07:00: WEB 10.23.88.130 hacia DB 10.23.88.146:3306 mediante WEB_TO_DB_3306 (4), con 379 B recibidos y 297 B enviados. Cierre TCP reset from client; la prueba abre TCP sin autenticación SQL. Los intentos negativos no aparecen en los registros consultados; los timeouts son consistentes con las políticas, pero por sí solos no identifican la causa del bloqueo ni prueban todos los puertos.

Respaldo final recibido: 24/09/2026 14:03. Confirma `set status disable` en TEMP_WEB_UPDATES; copia sanitizada publicada.
