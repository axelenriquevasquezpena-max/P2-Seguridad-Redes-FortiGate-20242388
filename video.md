# Guion de video — duración objetivo 9 minutos

Este guion orienta la demostración propia; no sustituye las pruebas. Deben verse rostro, voz, fecha y hora. No mostrar contraseñas, claves privadas ni archivos de licencia. Ensayar antes para evitar sobrepasar 10 minutos.

| Tiempo | Mostrar | Explicación |
|---|---|---|
| 0:00–0:35 | Fecha, hora, rostro y topología | Nombre, matrícula, propósito y tres segmentos |
| 0:35–1:25 | GUI FortiGate: interfaces/DHCP; switch | VLAN 10 /25, WEB y DB en /28; seguridad de puertos |
| 1:25–2:15 | GUI: políticas, ruta y NAT | 443 permitido, DB3306 denegado, WEB→DB3306; declarar excepción de actualizaciones y su estado |
| 2:15–3:00 | Kali: catálogo HTTPS y prueba DB3306; GUI logs | Página funcional y denegación correlacionada |
| 3:00–3:35 | Perfil SSL y error de importación | Aclarar: HTTPS existe, pero inspección profunda no fue demostrada; las siguientes pruebas son HTTP |
| 3:35–4:30 | File Filter, control.txt y putty.exe; log | Texto permitido y ejecutable bloqueado por File Filter |
| 4:30–5:10 | Application Control y log | Firefox identificado en Monitor, acción pass |
| 5:10–6:15 | DoS: perfil, prueba breve y evento | Mostrar umbral y tcp_syn_flood; verificar que vuelve a responder |
| 6:15–7:25 | IPS: patrón HTTP y registro | Firma detectada; cuarentena y petición normal bloqueada |
| 7:25–8:20 | Capturas fechadas de recuperación; GUI | Explicar caducidad de cinco minutos; si es una captura previa, decirlo |
| 8:20–9:00 | Repositorio y conclusiones | Evidencias, configuraciones sanitizadas y limitación pendiente |

Hacer File Filter y Application Control antes de cuarentena para que esta no interfiera. No repetir ataques durante los cinco minutos si se quiere verificar caducidad.

La espera puede omitirse mediante un corte claramente indicado y con horas visibles antes/después, sin simular continuidad. Alternativamente mostrar la captura previa como tal. No presentar un log antiguo como recién generado.

Toda configuración y demostración de FortiGate debe ser por GUI. Las terminales corresponden exclusivamente a Kali, Linux y switch.

Frase de cierre sugerida: “Se demostraron segmentación, IPS y cuarentena por HTTP, filtrado de ejecutables, identificación de Firefox y protección SYN. La inspección profunda HTTPS permanece pendiente por la limitación documentada del entorno; no la presento como completada”.

