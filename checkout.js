document.addEventListener("DOMContentLoaded", function () {
  // Load cart items
  fetch("get_cart.php", { credentials: "include" })
    .then((res) => res.json())
    .then((data) => {
      const container = document.getElementById("checkout-items-container");
      const grandTotalSpan = document.getElementById("grand-total");
      const cartDataInput = document.getElementById("cart-data");

      if (data.status !== "success" || !Array.isArray(data.items)) {
        container.innerHTML = "<p>Your cart is empty or could not be loaded.</p>";
        return;
      }

      const cart = data.items;
      let grandTotal = 0;

      cart.forEach((item) => {
        const itemDiv = document.createElement("div");
        itemDiv.classList.add("checkout-item");

        itemDiv.innerHTML = `
          <img class="item-img" src="${item.image}" alt="${item.product_name}" />
          <div class="item-details">
            <span class="item-name">${item.product_name}</span>
            <div class="item-qty">Quantity: ${item.quantity}</div>
            <div class="item-price">Price: ZMW ${item.price}</div>
          </div>
        `;

        container.appendChild(itemDiv);
        grandTotal += item.price * item.quantity;
      });

      grandTotalSpan.textContent = grandTotal.toFixed(2);

      // Save cart data before form submission
      const form = document.getElementById("checkout-form");
      form.addEventListener("submit", () => {
        cartDataInput.value = JSON.stringify(cart);
      });
    })
    .catch((err) => {
      console.error("Error loading cart:", err);
      document.getElementById("checkout-items-container").innerHTML =
        "<p>Failed to load cart items.</p>";
    });
});

// Toggle payment options dropdown
function toggleDropdown() {
  const options = document.getElementById("payment-options");
  options.style.display = options.style.display === "flex" ? "none" : "flex";
}

// Show appropriate form fields based on selected payment method
function showForm(method) {
  document.getElementById("payment_method").value = method;
  document.getElementById("checkout-form").style.display = "flex";

  const bankFields = document.getElementById("bank-fields");
  const mobileFields = document.getElementById("mobile-fields");

  if (method === "Bank") {
    bankFields.style.display = "block";
    mobileFields.style.display = "none";
  } else if (method === "Mobile Money") {
    mobileFields.style.display = "block";
    bankFields.style.display = "none";
  }

  document.getElementById("payment-options").style.display = "none";
}
