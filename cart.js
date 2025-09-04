document.addEventListener("DOMContentLoaded", function () {
    fetchCartItems(); // Fetch and display cart items when page loads
});

function fetchCartItems() {
    fetch("get_cart.php", {
        method: "GET",
        credentials: "include" // Send cookies/session
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            displayCartItems(data.items);
        } else {
            alert("Failed to load cart items: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
}



function displayCartItems(items) {
    const cartItemsContainer = document.getElementById("cart-items-container");
    const cartTotalElement = document.getElementById("cart-total");
    let total = 0;

    cartItemsContainer.innerHTML = ""; // Clear previous content

    items.forEach(item => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;

        const cartItem = document.createElement("div");
        cartItem.classList.add("cart-item");

        cartItem.innerHTML = `
            <img src="${item.image}" alt="${item.product_name}">
            <div class="cart-item-details">
                <h3>${item.product_name}</h3>
                <p>Quantity: ${item.quantity}</p>
            </div>
            <div class="cart-item-price">ZMW ${itemTotal.toFixed(2)}</div>
            <button class="remove-item-btn" data-id="${item.id}">Remove</button>
        `;

        cartItemsContainer.appendChild(cartItem);
    });

    cartTotalElement.innerText = "ZMW " + total.toFixed(2);

    document.querySelectorAll(".remove-item-btn").forEach(button => {
        button.addEventListener("click", function () {
            const itemId = this.getAttribute("data-id");
            removeItem(itemId);
        });
    });
}

function removeItem(itemId) {
    fetch("remove_item.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ id: itemId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Item removed from cart!");
            window.location.reload();
        } else {
            alert("Failed to remove item: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
}

function clearCart() {
    // Track as abandoned
    trackCartAction('abandoned');

    // Clear client-side
    localStorage.removeItem('cart');
    document.getElementById('cart-items-container').innerHTML = '';
    document.getElementById('cart-total').textContent = 'ZMW 0.00';

    // Clear server-side
    fetch("clear_cart.php", {
        method: "POST",
        credentials: "include",
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert("Cart cleared successfully!");
            window.location.reload();
        } else {
            alert("Failed to clear cart: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
}

function proceedToCheckout() {
    // Track as recovered
    trackCartAction('recovered');

    // Proceed to checkout
    window.location.href = "checkout.php";
}

// Tracking user interaction (optional example below — you can adjust or remove it)
fetch('user_interactions.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        product_id: 5,
        action: 'click'
    })
})
.then(response => response.json())
.then(data => {
    console.log('Tracking response:', data);
})
.catch(err => console.error('Tracking error:', err));

function trackCartAction(action) {
    fetch('track_cart_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=${action}&user_id=${getLoggedInUserId()}`
    }).catch(error => console.error('Tracking failed:', error));
}

function getLoggedInUserId() {
    // Replace with real user ID logic
    return window.userId || 0;
}
