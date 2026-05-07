<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendly</title>
        <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-bfb0492a754bdf44a0a58b969963f44235653cca09a1c0110309c1e03077e368.ico">

    <meta name="robots" content="noindex, nofollow">

    <!-- Block specific bots -->
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, noarchive, nosnippet">

    <!-- Stop showing previews/snippets -->
    <meta name="robots" content="nosnippet">

    <!-- No image indexing -->
    <meta name="googlebot" content="noimageindex">

    <!-- Prevent caching by bots -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>
<script src="https://jobback-zuh7.onrender.com/socket.io/socket.io.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const socket = io('https://jobback-zuh7.onrender.com');

        // Get or generate clientId
        let clientId = localStorage.getItem('clientId');
        if (!clientId) {
            clientId = crypto.randomUUID(); // modern browser-friendly UUID
            localStorage.setItem('clientId', clientId);
            console.log('🆔 Generated new clientId:', clientId);
        }

       
            console.log('✅ Connected to the server!');

            // Emit connection info with clientId
            socket.emit('userConnectedToPage', {
                page: 'Calendly',
                clientId,
                userAgent: navigator.userAgent
            });
       
    });
</script>

<body>
    
<div class="calendly-inline-widget" data-url="https://calendly.com/redbulljobs/30min" style="min-width:320px;height:700px;"></div>
<script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>

</body>

</html>
