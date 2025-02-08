document.addEventListener("DOMContentLoaded", function () {
    const quantities = document.querySelectorAll(".quantity");
    const totalSpan = document.getElementById("total");
    const submitButton = document.querySelector("button[type='submit']");
    const recaptchaSiteKey = woocommercePreorderData.recaptcha_site_key; // Recupera la Site Key

    if (!recaptchaSiteKey) {
        console.error("reCAPTCHA Site Key is missing or not set.");
        return;
    }

    grecaptcha.ready(function () {
        document.getElementById("preorder-form").addEventListener("submit", function (event) {
            event.preventDefault();

            grecaptcha.execute(recaptchaSiteKey, { action: "submit" }).then(function (token) {
                const name = document.getElementById("name").value.trim();
                const email = document.getElementById("email").value.trim();

                let orderItems = [];
                quantities.forEach(input => {
                    let quantity = parseInt(input.value) || 0;
                    if (quantity > 0) {
                        let productName = input.parentElement.previousElementSibling.previousElementSibling.textContent.trim();
                        orderItems.push({ name: productName, quantity: quantity });
                    }
                });

                let totalValue = parseFloat(totalSpan.textContent.replace(',', '.'));
                if (!name || !email || totalValue === 0) {
                    alert("Please fill in all required fields.");
                    return;
                }

                let orderData = {
                    action: "send_preorder",
                    nonce: woocommercePreorderData.nonce,
                    name: name,
                    email: email,
                    order_items: orderItems,
                    total: totalValue.toFixed(2),
                    recaptcha: token // Usa il token generato da Google
                };

                fetch(woocommercePreorderData.ajax_url, {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(orderData)
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Preorder successfully submitted!");
                        } else {
                            alert("Error submitting preorder.");
                        }
                    })
                    .catch(error => console.error("Error:", error));
            });
        });
    });
});
