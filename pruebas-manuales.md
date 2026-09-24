# Registro de comandos de prueba

Transcripción de comandos utilizados o indicados durante las pruebas. No es un script automático ni un historial completo exportado. Las evidencias están en pruebas.md. Ejecutar cada prueba de forma manual y separada, solo en esta topología autorizada.

## Kali: conectividad

    nc -vz -w 5 10.23.88.130 443
    nc -vz -w 5 10.23.88.146 3306

Correlacionar aceptación/denegación con GUI del firewall. Un timeout aislado no acredita filtrado.

## WEB: PHP y HTTPS

    sudo php -l /etc/p2/database.php
    php -l /var/www/p2/index.php
    sudo curl -i --cacert /etc/nginx/ssl/web-p2.crt https://10.23.88.130/

## WEB: consulta a DB

    mariadb --connect-timeout=5 -h 10.23.88.146 -u webapp -p laboratorio_p2 -e "SELECT * FROM productos;"

La contraseña se introduce interactivamente y no se documenta.

## Kali: solicitud legítima y patrón IPS

Primero solicitar el catálogo normal:

    curl -i --max-time 10 http://10.23.88.130/

Prueba de cabecera dirigida solo a WEB del laboratorio:

    curl -i --max-time 10 -H "User-Agent: ' OR 1=1 -- " http://10.23.88.130/

Después, repetir únicamente la petición legítima para comprobar cuarentena. Tras cinco minutos sin nuevas pruebas de ataque, verificar recuperación. El cuerpo de la respuesta distingue catálogo y bloqueo, aunque ambos puedan devolver HTTP 200.

## Kali: prueba SYN breve

Se indicó la siguiente prueba acotada: 500 paquetes, intervalo de 10.000 microsegundos, aproximadamente 100 paquetes/s durante cinco segundos.

    sudo hping3 -S -p 443 -i u10000 -c 500 10.23.88.130

No usar --flood ni dirigir a Internet. El evento observado está documentado, pero no existe captura completa del comando ejecutado: no atribuir a esta transcripción el carácter de evidencia de ejecución.

## Descarga de control y ejecutable

PuTTY se obtuvo de su sitio oficial, sin ejecutarlo. En PowerShell de Windows:

    scp "$env:USERPROFILE\Downloads\putty.exe" axel@10.23.88.130:/home/axel/putty.exe

En WEB:

    sudo mkdir -p /var/www/p2/downloads
    sudo install -m 644 /home/axel/putty.exe /var/www/p2/downloads/putty.exe
    echo "Archivo de texto permitido - P2" | sudo tee /var/www/p2/downloads/control.txt

En Firefox de Kali, visitar por separado:

    http://10.23.88.130/downloads/control.txt
    http://10.23.88.130/downloads/putty.exe

Comprobar File Filter blocked y Application Control pass. No ejecutar el binario. No se incluye el ejecutable en el repositorio.

## Certificado: diagnóstico realizado

En WEB se exportó una copia compatible (contraseña interactiva):

    sudo openssl pkcs12 -export -legacy -inkey /etc/nginx/ssl/web-p2.key -in /etc/nginx/ssl/web-p2.crt -out /home/axel/web-p2-compatible.p12 -name WEB_P2 -keypbe PBE-SHA1-3DES -certpbe PBE-SHA1-3DES -macalg sha1

Se verificó:

    openssl pkcs12 -legacy -info -noout -in /home/axel/web-p2-compatible.p12

La exportación compatible fue una prueba de diagnóstico; no resolvió la restricción de tamaño de clave. No publicar .p12 ni .key.

## Referencia de descarga

[PuTTY — página oficial](https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html).

