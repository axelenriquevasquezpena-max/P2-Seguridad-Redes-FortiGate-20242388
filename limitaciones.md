# Limitación de inspección HTTPS

WEB sirve HTTPS y Usuarios alcanza 443. La política utiliza no-inspection: permitir una sesión cifrada no implica inspeccionar su contenido.

La evaluación 7.0.9 muestra vencimiento 2026/10/05. Al importar RSA 2048 aparece:

> Incorrect certificate file key size for CA/LOCAL/REMOTE cert.

Se validó el PKCS#12 en WEB:

    openssl pkcs12 -legacy -info -noout -in /home/axel/web-p2-compatible.p12

Mostró MAC SHA1, Certificate bag y Shrouded Keybag con 3DES, sin error de contraseña. Tras otra transferencia persistió el rechazo. El error previo de lectura PKCS#12 es distinto. Aumentar RAM a 2 GiB no eliminó el error de tamaño de clave.

Fortinet documenta cifrado reducido en esta evaluación; el síntoma es consistente con esa restricción. Se solicitó una evaluación completa, sin respuesta incorporada al cierre del documento.

Fortinet_CA_SSL es una CA integrada para un modo de inspección de clientes, pero no elimina restricciones de licencia. No se obtuvo una prueba exitosa con ella. No se redujo la seguridad TLS para simular cumplimiento.

IPS, cuarentena, File Filter y Application Control se probaron por HTTP. No se presume aceptación académica de esa alternativa ni se declara DPI HTTPS completado. Si llega una licencia compatible, deberá instalarse por GUI, configurarse inspección y confianza del cliente y repetirse las pruebas en 443.

## Referencias

- [Evaluación FortiGate-VM 7.0](https://docs.fortinet.com/document/fortigate-private-cloud/7.0.0/xen-administration-guide/504166/fortigate-vm-evaluation-license).
- [Inspección profunda con certificado integrado](https://community.fortinet.com/fortigate-3/technical-tip-how-to-enable-deep-inspection-and-import-a-certificate-in-the-browser-98489).
- [Limitaciones de inspección SSL en evaluación permanente](https://community.fortinet.com/support-forum-92/does-fortigate-vm-permanent-trial-mode-support-ssl-deep-inspection-129709). Explicación complementaria de otra generación; no una validación específica de 7.0.9.
- [Contacto comercial](https://www.fortinet.com/corporate/about-us/request-a-quote).

