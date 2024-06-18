document.addEventListener('DOMContentLoaded', async () => {
    await loadCartItems();
    await loadUserDetails();

    document.getElementById('process-order-button').addEventListener('click', processOrder);
});

async function loadCartItems() {
    // Fetch cart items from local storage or server
    const response = await fetch('php/getCartItems.php');
    const cartItems = await response.json();
    //const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    const cartItemsContainer = document.getElementById('cart-items');
    let totalPrice = 0;

    cartItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.classList.add('cart-item');
        itemElement.innerHTML = `
            <div>${item.name}</div>
            <div>Quantity: ${item.quantity}</div>
            <div>Price: $${item.price}</div>
        `;
        cartItemsContainer.appendChild(itemElement);
        totalPrice += item.quantity * item.price;
    });

    document.getElementById('total-price').innerText = `Total Price: $${totalPrice.toFixed(2)}`;
}

async function loadUserDetails() {
    try {
        const response = await fetch('php/getUserDetails.php');
        const userDetails = await response.json();

        // Load bank details if available
        if (userDetails.bank_details) {
            document.getElementById('name-on-card').value = userDetails.bank_details.name_on_card;
            document.getElementById('card-number').value = userDetails.bank_details.card_number;
            document.getElementById('expiry-date-month').value = userDetails.bank_details.expiry_date_month;
            document.getElementById('expiry-date-year').value = userDetails.bank_details.expiry_date_year;
        }

        // Load delivery details if available
        if (userDetails.delivery_details) {
            document.getElementById('mobile-number').value = userDetails.delivery_details.mobile_number;
            document.getElementById('street-address').value = userDetails.delivery_details.street_address;
            document.getElementById('suburb').value = userDetails.delivery_details.suburb;
            document.getElementById('city').value = userDetails.delivery_details.city;
            document.getElementById('province').value = userDetails.delivery_details.province;
            document.getElementById('postal-code').value = userDetails.delivery_details.postal_code;
        }
    } catch (error) {
        console.log(error);
        console.error('Error loading user details:', error);
    }
}

function validateForm() {
    const bankDetailsForm = document.getElementById('bank-details-form');
    const deliveryDetailsForm = document.getElementById('delivery-details-form');

    return bankDetailsForm.checkValidity() && deliveryDetailsForm.checkValidity();
}

async function processOrder() {
  /*
    if (!validateForm()) {
        alert('Please fill in all required fields correctly.');
        return;
    }
*/
    const formData = new FormData();
    formData.append('bank_details', JSON.stringify({
        name_on_card: document.getElementById('name-on-card').value,
        card_number: document.getElementById('card-number').value,
        expiry_date_month: document.getElementById('expiry-date-month').value,
        expiry_date_year: document.getElementById('expiry-date-year').value
    }));
    formData.append('delivery_details', JSON.stringify({
        mobile_number: document.getElementById('mobile-number').value,
        street_address: document.getElementById('street-address').value,
        suburb: document.getElementById('suburb').value,
        city: document.getElementById('city').value,
        province: document.getElementById('province').value,
        postal_code: document.getElementById('postal-code').value
    }));
    for (var pair of formData.entries()) {
        console.log(pair[0]+ ', ' + pair[1]); 
    }

    const response = await fetch('php/getCartItems.php');
    const cartItems = await response.json();

    
    formData.append('cart_items', JSON.stringify(cartItems));

    for (var pair of formData.entries()) {
        console.log(pair[0]+ ', ' + pair[1]); 
    }
    
    try {
        const response = await fetch('php/processOrder.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        
        if (result.success) {
            alert('Order processed successfully!');
            window.location.href = 'index.html';
        } else {
            alert('Error processing order: ' + result.message);
        }
    } catch (error) {
        console.error('Error processing order catch:', error.message);
    }
}
