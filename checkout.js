// Function to display cart items on the checkout page
function displayCheckoutItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const checkoutItemsContainer = document.getElementById('checkout-items');
    const checkoutTotal = document.getElementById('checkout-total');

    checkoutItemsContainer.innerHTML = '';
    let total = 0;

    // Populate checkout items and calculate the total
    cart.forEach(item => {
        const itemTotal = (item.price * item.quantity).toFixed(2);
        total += parseFloat(itemTotal);

        const checkoutItem = document.createElement('div');
        checkoutItem.classList.add('checkout-item');
        checkoutItem.innerHTML = `
            <span>${item.name} (x${item.quantity})</span>
            <span>RM${itemTotal}</span>
        `;
        checkoutItemsContainer.appendChild(checkoutItem);
    });

    // Update the total amount
    checkoutTotal.innerText = `RM${total.toFixed(2)}`;

    // Save the total amount to localStorage
    localStorage.setItem('cartTotal', total.toFixed(2));
}

// Function to handle placing the order
function placeOrder() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert("Your cart is empty!");
        return;
    }

    // Save the total amount to localStorage before redirecting
    const total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0).toFixed(2);
    localStorage.setItem('cartTotal', total);

    alert("Please fill up your details before make a payment.");
    window.location.href = 'receipt.html';
}

// Function to handle cancelling the order
function cancelOrder() {
    const confirmation = confirm("Are you sure you want to cancel your order?");
    if (confirmation) {
        localStorage.removeItem('cart');
        alert("Your order has been cancelled.");
        window.location.href = 'index.html';
    }
}

// Initialize the checkout page
window.addEventListener('load', () => {
    displayCheckoutItems();
    document.getElementById('place-order').addEventListener('click', placeOrder);
    document.getElementById('cancel-order').addEventListener('click', cancelOrder);
});
