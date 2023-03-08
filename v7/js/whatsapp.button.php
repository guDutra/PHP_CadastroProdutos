<!-- GetButton.io widget -->
<script type="text/javascript">
    (function () {
        var options = {
            facebook: "<?= SITE_SOCIAL_FB_PAGE_ID?>", // Facebook page ID
            whatsapp: "<?= SITE_ADDR_WHATS ?>", // WhatsApp number
            email: "<?= SITE_ADDR_EMAIL ?>", // Email
            company_logo_url: "<?= $favicon; ?>", // URL of company logo (png, jpg, gif)
            call_to_action: "Precisa de ajuda?", // Call to action
            button_color: "#FFC107", // Color of button
            position: "left", // Position may be 'right' or 'left'
            order: "whatsapp,facebook,email", // Order of buttons
            pre_filled_message: "Olá! Estou no Portal Super Pesados Brasil tenho uma dúvida, pode me ajudar?", // WhatsApp pre-filled message
        };
        var proto = document.location.protocol,
            host = "getbutton.io",
            url = proto + "//static." + host;
        var s = document.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = url + '/widget-send-button/js/init.js';
        s.onload = function () {
            WhWidgetSendButton.init(host, proto, options);
        };
        var x = document.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    })();
</script>