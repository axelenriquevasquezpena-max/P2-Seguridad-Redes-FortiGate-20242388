# Configuraciones originales por incorporar

Se recibieron y revisaron cuatro archivos reales de WEB el 24/09/2026: nginx-p2.conf, nginx-p2-http-lab.conf, web-netplan.yaml e index.php. No contienen contraseñas incrustadas. index.php carga las credenciales desde /etc/p2/database.php, que no se publica. Las exportaciones de DB, switch y FortiGate siguen pendientes.

El estudiante reportó guardar un respaldo de FortiGate. Su ruta local no fue proporcionada; todavía no se incorporó.

| Archivo público sugerido | Obtener del equipo | Tratamiento |
|---|---|---|
| fortigate-sanitizado.conf | Backup por GUI FortiGate | Retirar claves, certificados privados, usuarios/secretos sensibles y valores ENC |
| switch-running-config.txt | show running-config en SW-P2-2024-2388 | Retirar contraseñas y secretos |
| web-netplan.yaml | /etc/netplan/50-cloud-init.yaml de WEB | Revisar datos |
| db-netplan.yaml | Mismo archivo en DB | Revisar datos |
| nginx-p2.conf | /etc/nginx/sites-available/p2 | Copia real |
| nginx-p2-http-lab.conf | /etc/nginx/sites-available/p2-http-lab | Identificar como temporal |
| index.php | /var/www/p2/index.php | Revisar que no incluya credenciales |
| database.example.php | /etc/p2/database.php | Sustituir contraseña por placeholder |
| mariadb-60-p2.cnf | /etc/mysql/mariadb.conf.d/60-p2.cnf | Copia real |
| productos.sql | Esquema y filas de laboratorio_p2 | No incluir tablas de usuarios ni credenciales |
| web-p2.crt | Certificado público, opcional | No incluir su clave privada |

Antes de copiar el running-config del switch puede usarse terminal length 0 para evitar paginación. Ese comando es del switch, no de FortiGate.

Guardar scripts de instalación originales si se conservaron. El registro de comandos incluido en scripts es una transcripción documental, no la exportación del historial completo.

No agregar respaldos cifrados como única evidencia revisable. Conservarlos privadamente para recuperación.

