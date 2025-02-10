document.addEventListener("DOMContentLoaded", function () {
    const quantities = document.querySelectorAll(".quantity");
    const totalSpan = document.getElementById("total");
    const submitButton = document.querySelector("button[type='submit']");
    
    if (!totalSpan || !submitButton) {
        console.error("Required elements not found on the page.");
        return;
    }

    const locale = woocommercePreorderData.locale.replace('_', '-');

    function updateTotal() {
        let total = 0;
        quantities.forEach(input => {
            let price = input.dataset.price.replace(',', '.'); // virgola in punto
            price = parseFloat(price) || 0; // conversione numero
            let quantity = parseInt(input.value) || 0;
            total += price * quantity;
        });
    
        let formatter = new Intl.NumberFormat(locale, {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    
        totalSpan.textContent = formatter.format(total);
    
        document.getElementById("preorder-total").value = total.toFixed(2);
    }
    

    quantities.forEach(input => {
        input.addEventListener("input", updateTotal);
    });

    updateTotal();

    // invio del modulo
    document.getElementById("preorder-form").addEventListener("submit", function (event) {
        event.preventDefault();

        let name = document.getElementById("name").value.trim();
        let email = document.getElementById("email").value.trim();
        let notes = document.getElementById("notes").value.trim();


        let orderItems = [];
        quantities.forEach((input, index) => {
            let quantity = parseInt(input.value) || 0;
            if (quantity > 0) {
                let productName = input.parentElement.previousElementSibling.previousElementSibling.textContent.trim();
                orderItems.push({ name: productName, quantity: quantity });
            }
        });

        //let totalValue = parseFloat(totalSpan.textContent.replace(',', '.'));
        //let totalValue = parseFloat(totalSpan.textContent.replace(/\./g, '').replace(',', '.'));
        //let totalValue = parseFloat(totalSpan.textContent.replace(/\./g, '').replace(',', '.'));
        let totalValue = totalSpan.textContent.replace(/\./g, '').replace(',', '.');


        if (!name || !email || totalValue === 0) {
            alert("Please fill in all required fields.");
            return;
        }

        //  form-data
        let formData = new URLSearchParams();
        formData.append("action", "send_preorder");
        formData.append("nonce", woocommercePreorderData.nonce);
        formData.append("name", name);
        formData.append("email", email);
        formData.append("notes", notes);
        formData.append("total", totalValue);

        orderItems.forEach((item, index) => {
            formData.append(`order_items[${index}][name]`, item.name);
            formData.append(`order_items[${index}][quantity]`, item.quantity);
        });

        // richiesta ajax
        fetch(woocommercePreorderData.ajax_url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Preordine inviato!");
                } else {
                    console.log("Si è verificato un errore durante l'invio del preordine");
                }
            })
            .catch(error => console.error("Errore:", error));
    });
});
