# Estado de configuraciones y verificaciones

Se publican archivos reales revisados de WEB, DB, switch y FortiGate. Las credenciales y claves privadas se excluyen.

- WEB: index.php, nginx-p2.conf, nginx-p2-http-lab.conf y web-netplan.yaml.
- DB: db-netplan.yaml, mariadb-50-server.cnf, mariadb-60-p2.cnf y productos.sql (tabla y tres productos; no usuarios).
- Switch: switch-running-config.txt. Las salidas aportadas de show vlan brief y show interfaces trunk confirman VLAN 10/20/30 activas y en reenvío por Gi0/0, nativa 999. El estudiante reportó guardar startup-config.
- FortiGate: fortigate-sanitizado.conf, respaldo GUI final 24/09/2026 14:03. TEMP_WEB_UPDATES tiene status disable. Se omitieron credenciales, claves y certificados; no es restaurable directamente.
- [Verificación NAT y pruebas finales](verificacion-nat.md).

Pendiente: enlace del video al inicio del README y en TXT, revisión final de enlaces/documentación. DPI del contenido HTTPS no se completó. Las pruebas negativas abarcan DB:22, DB:80 y una dirección externa:80, no todos los destinos posibles.
