// Function to add items to cart
function addToCart(name, price) {
    // Retrieve cart from localStorage or initialize if it doesn't exist
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let item = cart.find(item => item.name === name);

    // Convert price to a float
    price = parseFloat(price);
    if (isNaN(price)) {
        console.error("Invalid price format:", price);
        return;
    }

    if (item) {
        // Increase quantity by 1 if item already in cart
        item.quantity += 1;
    } else {
        // Add new item to cart with quantity of 1
        cart.push({ name, price: price, quantity: 1 });
    }

    // Save updated cart to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
}

// Function to update cart count and total in navbar
function updateCartDisplay() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0).toFixed(2);

    // Update the cart total in the navbar
    const cartTotalElement = document.getElementById('cart-total');
    if (cartTotalElement) {
        cartTotalElement.innerText = `RM${total}`;
    }
}

// Function to display cart items on yourcart.html
function displayCartItems() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let cartItemsContainer = document.getElementById('cart-items');
    let totalAmount = document.getElementById('cart-total-amount');

    cartItemsContainer.innerHTML = '';
    let total = 0;

    cart.forEach(item => {
        let itemTotal = (item.price * item.quantity).toFixed(2);
        total += parseFloat(itemTotal);

        cartItemsContainer.innerHTML += `
            <div class="cart-item">
                <span>${item.name} (x${item.quantity})</span>
                <span>RM${itemTotal}</span>
            </div>
        `;
    });

    if (totalAmount) {
        totalAmount.innerText = `Total: RM${total.toFixed(2)}`;
    }
    updateCartDisplay();
}

// Function to clear the cart
function clearCart() {
    localStorage.removeItem('cart');
    displayCartItems();
    updateCartDisplay();
}

// Function to handle checkout process
function checkoutCart() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    if (cart.length === 0) {
        alert("Your cart is empty!");
    } else {
        alert("Thank you for your purchase!");
        clearCart(); // Clears cart after checkout
    }
}

// Load cart data when page loads
window.addEventListener('load', () => {
    updateCartDisplay();
    if (document.getElementById('cart-items')) {
        displayCartItems();
    }

    // Add event listener for the clear cart button if on yourcart.html
    const clearCartButton = document.getElementById('clear-cart');
    if (clearCartButton) {
        clearCartButton.addEventListener('click', clearCart);
    }

    // Add event listener for the checkout button if on yourcart.html
    const checkoutButton = document.getElementById('checkout-cart');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', checkoutCart);
    }
});

// Event listener for Add to Cart buttons
document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', () => {
        const name = button.getAttribute('data-name');
        const price = button.getAttribute('data-price');
        addToCart(name, price);
    });
});
