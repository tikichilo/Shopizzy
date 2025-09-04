// === Utility Functions ===

/**
 * Show messages to the user
 * @param {string} msg - Message to display
 * @param {string} type - Message type (success, error, info)
 */
function displayMessage(msg, type) {
    const container = document.getElementById("message-container");
    if (!container) return;
    
    container.textContent = msg;
    container.className = type;
    
    // Clear message after delay
    setTimeout(() => {
        container.textContent = "";
        container.className = "";
    }, 5000);
}

/**
 * Animate cart dot when item is added
 */
function animateCartDot() {
    const cartDot = document.getElementById("cartDot");
    if (!cartDot) return;
    
    cartDot.classList.add("pop");
    setTimeout(() => cartDot.classList.remove("pop"), 300);
}

/**
 * Sanitize string for safe HTML insertion
 * @param {string} str - String to sanitize
 * @return {string} Sanitized string
 */
function sanitizeString(str) {
    if (!str) return '';
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
}

// === Cart Management ===

// === Cart Management ===

/**
 * Add product to cart
 * @param {string} productName - Product name
 * @param {number} productPrice - Product price
 * @param {string|number} productId - Product ID
 * @param {string} productImage - Product image path
 */
function addToCart(productName, productPrice, productId, productImage) {
    // Validate inputs
    if (!productName || isNaN(parseFloat(productPrice)) || !productId) {
        displayMessage("Invalid product data", "error");
        return;
    }
    
    // Ensure image path is valid
    const imagePath = productImage.includes("/") ? productImage : "uploads/" + productImage;
    
    // Show loading state
    const buyBtn = document.querySelector(`[data-id="${productId}"].add-to-cart-btn`);
    if (buyBtn) {
        buyBtn.disabled = true;
        buyBtn.textContent = "Adding...";
    }

    fetch("add_to_cart.php", {
        method: "POST",
        credentials: "include", // Important: include credentials to send cookies
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            product_name: productName,
            price: productPrice,
            quantity: 1,
            image: imagePath
        })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        if (data.status === "success") {
            displayMessage("Item added to cart!", "success");
            updateCartDisplay();
            animateCartDot();
        } else {
            displayMessage("Error: " + (data.message || "Unknown error"), "error");
            // If not logged in, redirect to login page
            if (data.message === "User not logged in") {
                window.location.href = "login.html";
            }
        }
    })
    .catch(err => {
        console.error("Add to cart error:", err);
        displayMessage("An error occurred adding item to cart.", "error");
    })
    .finally(() => {
        // Reset button state
        if (buyBtn) {
            buyBtn.disabled = false;
            buyBtn.textContent = "Add to Cart";
        }
    });
}

/**
 * Remove item from cart
 * @param {string|number} itemId - Cart item ID to remove
 */
function removeItem(itemId) {
    if (!itemId) {
        displayMessage("Invalid item ID", "error");
        return;
    }

    fetch("remove_from_cart.php", {
        method: "POST",
        credentials: "include", // Important: include credentials to send cookies
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ item_id: itemId })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        if (data.status === "success") {
            displayMessage("Item removed from cart!", "success");
            updateCartDisplay();
            updateCartDot();
        } else {
            displayMessage("Error removing item: " + (data.message || "Unknown error"), "error");
            // If not logged in, redirect to login page
            if (data.message === "User not logged in") {
                window.location.href = "login.html";
            }
        }
    })
    .catch(err => {
        console.error("Error removing item:", err);
        displayMessage("An error occurred while removing item.", "error");
    });
}

/**
 * Update cart display with current items
 */
function updateCartDisplay() {
    const cartItemsContainer = document.getElementById("cart-items");
    const cartTotalElement = document.getElementById("cart-total");
    
    if (!cartItemsContainer || !cartTotalElement) return;
    
    // Show loading indicator
    cartItemsContainer.innerHTML = "<p>Loading cart...</p>";

    fetch("get_cart_items.php", { 
        credentials: "include" // Important: include credentials to send cookies
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        // Check for session error
        if (data.status === "error") {
            cartItemsContainer.innerHTML = `<p>${data.message || "Please log in to view your cart"}</p>`;
            cartTotalElement.textContent = `ZMW 0.00`;
            return;
        }
        
        // Process items if available
        const items = data.items || [];
        cartItemsContainer.innerHTML = "";
        let total = 0;
        
        if (items.length === 0) {
            cartItemsContainer.innerHTML = "<p>Your cart is empty</p>";
            cartTotalElement.textContent = `ZMW 0.00`;
            return;
        }

        items.forEach(item => {
            const itemTotal = parseFloat(item.price) * parseInt(item.quantity);
            total += itemTotal;

            const cartItem = document.createElement("div");
            cartItem.className = "cart-item";
            
            // Safely create cart item HTML
            cartItem.innerHTML = `
                <h3>${sanitizeString(item.product_name)}</h3>
                <p>Quantity: ${parseInt(item.quantity)}</p>
                <p>ZMW ${parseFloat(item.price).toFixed(2)}</p>
                <button class="remove-item" data-id="${item.id}">Remove</button>
            `;
            
            cartItemsContainer.appendChild(cartItem);
        });

        cartTotalElement.textContent = `ZMW ${total.toFixed(2)}`;
        
        // Add event listeners to new remove buttons
        attachRemoveItemListeners();
    })
    .catch(err => {
        console.error("Cart display error:", err);
        cartItemsContainer.innerHTML = "<p>Failed to load cart items</p>";
    });
}

/**
 * Attach event listeners to cart remove buttons
 */
function attachRemoveItemListeners() {
    document.querySelectorAll(".remove-item").forEach(button => {
        button.addEventListener("click", () => {
            const itemId = button.dataset.id;
            if (itemId) {
                removeItem(itemId);
            }
        });
    });
}

/**
 * Update cart notification dot
 */
function updateCartDot() {
    fetch("get_cart_count.php", { 
        credentials: "include" // Important: include credentials to send cookies
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        const cartDot = document.getElementById("cartDot");
        if (!cartDot) return;
        
        // Handle error response
        if (data.status === "error") {
            cartDot.textContent = "";
            cartDot.style.display = "none";
            return;
        }
        
        const count = parseInt(data.count) || 0;
        
        if (count > 0) {
            cartDot.textContent = count;
            cartDot.style.display = "inline-block";
        } else {
            cartDot.textContent = "";
            cartDot.style.display = "none";
        }
    })
    .catch(err => {
        console.error("Cart dot error:", err);
    });
}

/**
 * Log in user
 * @param {string} username - Username
 * @param {string} password - Password
 * @return {Promise} Promise resolving to login result
 */
function loginUser(username, password) {
    if (!username || !password) {
        displayMessage("Username and password required", "error");
        return Promise.reject(new Error("Missing credentials"));
    }
    
    displayMessage("Logging in...", "info");
    
    return fetch("login.php", {
        method: "POST",
        credentials: "include", // Important: include credentials to set session cookie
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        if (data.status === "success") {
            // Store user ID in session storage (for client-side access)
            sessionStorage.setItem("user_id", data.user_id);
            displayMessage("Login successful!", "success");
            
            // No need to call syncCartToDatabase here as we're using server-side sessions
            // Just update the cart display
            updateCartDisplay();
            updateCartDot();
            
            return data;
        } else {
            const errorMsg = data.message || "Unknown error";
            displayMessage("Login failed: " + errorMsg, "error");
            throw new Error(errorMsg);
        }
    })
    .catch(err => {
        console.error("Login error:", err);
        displayMessage("Login failed. Please try again.", "error");
        throw err;
    });
}

/**
 * Remove item from cart
 * @param {string|number} itemId - Cart item ID to remove
 */
function removeItem(itemId) {
    const userId = sessionStorage.getItem("user_id");
    
    if (!itemId) {
        displayMessage("Invalid item ID", "error");
        return;
    }

    if (!userId) {
        displayMessage("Please log in to manage your cart", "error");
        return;
    }

    fetch("remove_from_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ 
            item_id: itemId,
            user_id: userId // Add user_id to ensure removing from correct cart
        })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        if (data.status === "success") {
            displayMessage("Item removed from cart!", "success");
            updateCartDisplay();
            updateCartDot();
        } else {
            displayMessage("Error removing item: " + (data.message || "Unknown error"), "error");
        }
    })
    .catch(err => {
        console.error("Error removing item:", err);
        displayMessage("An error occurred while removing item.", "error");
    });
}


function updateCartDisplay() {
    const cartItemsContainer = document.getElementById("cart-items");
    const cartTotalElement = document.getElementById("cart-total");
    const userId = sessionStorage.getItem("user_id");
    
    if (!cartItemsContainer || !cartTotalElement) return;
    
    // Check if user is logged in
    if (!userId) {
        cartItemsContainer.innerHTML = "<p>Please log in to view your cart</p>";
        cartTotalElement.textContent = `ZMW 0.00`;
        return;
    }
    
    // Show loading indicator
    cartItemsContainer.innerHTML = "<p>Loading cart...</p>";

    fetch("get_cart_items.php", { 
        method: "POST",
        credentials: "include",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ user_id: userId }) // Pass user_id to get specific user's cart
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(items => {
        cartItemsContainer.innerHTML = "";
        let total = 0;
        
        if (!Array.isArray(items) || items.length === 0) {
            cartItemsContainer.innerHTML = "<p>Your cart is empty</p>";
            cartTotalElement.textContent = `ZMW 0.00`;
            return;
        }

        items.forEach(item => {
            const itemTotal = parseFloat(item.price) * parseInt(item.quantity);
            total += itemTotal;

            const cartItem = document.createElement("div");
            cartItem.className = "cart-item";
            
            // Safely create cart item HTML
            cartItem.innerHTML = `
                <h3>${sanitizeString(item.product_name)}</h3>
                <p>Quantity: ${parseInt(item.quantity)}</p>
                <p>ZMW ${parseFloat(item.price).toFixed(2)}</p>
                <button class="remove-item" data-id="${item.id}">Remove</button>
            `;
            
            cartItemsContainer.appendChild(cartItem);
        });

        cartTotalElement.textContent = `ZMW ${total.toFixed(2)}`;
        
        // Add event listeners to new remove buttons
        attachRemoveItemListeners();
    })
    .catch(err => {
        console.error("Cart display error:", err);
        cartItemsContainer.innerHTML = "<p>Failed to load cart items</p>";
    });
}


/**
 * Attach event listeners to cart remove buttons
 */
function attachRemoveItemListeners() {
    document.querySelectorAll(".remove-item").forEach(button => {
        button.addEventListener("click", () => {
            const itemId = button.dataset.id;
            if (itemId) {
                removeItem(itemId);
            }
        });
    });
}


// === Product Functions ===

/**
 * Sanitize a string to prevent XSS attacks
 * @param {string} str
 * @returns {string}
 */
function sanitizeString(str) {
    const temp = document.createElement('div');
    temp.textContent = str;
    return temp.innerHTML;
}

/**
 * Generate HTML for a product card
 * @param {Object} product - Product data
 * @return {string} HTML string
 */
function generateProductHTML(product) {
    if (!product || !product.name || !product.price || !product.id) {
        console.error("Invalid product data:", product);
        return "";
    }

    let extra = "";
    const price = parseFloat(product.price).toFixed(2);
    const category = sanitizeString(product.category || "");
    const image = sanitizeString(product.image || "");
    const name = sanitizeString(product.name);
    const id = sanitizeString(product.id);
    const tag = sanitizeString(product.tag || "");

    // Create category-specific details
    if (product.category === "phones") {
        extra = `
            <p><strong>Brand:</strong> ${sanitizeString(product.brand || "")}</p>
            <p><strong>Specs:</strong> ${sanitizeString(product.specs || "")}</p>
        `;
    } else if (product.category === "clothes") {
        extra = `
            <p><strong>Size:</strong> ${sanitizeString(product.size || "")}</p>
            <p><strong>Color:</strong> ${sanitizeString(product.color || "")}</p>
            <p><strong>Material:</strong> ${sanitizeString(product.material || "")}</p>
        `;
    } else if (product.category === "electronics") {
        extra = `
            <p><strong>Brand:</strong> ${sanitizeString(product.brand || "")}</p>
            <p><strong>Warranty:</strong> ${sanitizeString(product.warranty || "")}</p>
        `;
    }

 // Tag badge if tag exists
let tagHTML = "";
if (product.tag && product.tag.trim() !== "") {
    tagHTML = `<div class="special-tag-badge">${product.tag}</div>`;
}

    return `
        <div class="product ${category}" data-id="${id}" data-category="${category}" style="position: relative;">
            ${tagHTML}
            <img src="${image}" alt="${name}">
            <h3>${name}</h3>
            <p class="price">ZMW ${price}</p>
            ${extra}
            <button class="buy-btn add-to-cart-btn" 
                data-id="${id}" 
                data-name="${name}" 
                data-price="${price}" 
                data-image="${image}">
                Add to Cart
            </button>
        </div>
    `;
}

/**
 * Bind event handlers to product elements
 * @param {HTMLElement} container - Container element
 */
function bindAddToCartEvents(container) {
    if (!container) return;
    
    // Use event delegation for better performance
    container.addEventListener('click', (e) => {
        // Handle add to cart button clicks
        if (e.target.classList.contains('add-to-cart-btn')) {
            e.stopPropagation();
            const button = e.target;
            const { id, name, price, image } = button.dataset;
            
            addToCart(name, parseFloat(price), id, image);
            
            const parent = button.closest('.product');
            if (parent) {
                const category = parent.dataset.category;
                submitInteractionForm({ productId: parseInt(id), category, action: 'add_to_cart' });
            }
        }
        
        // Handle product click (delegation)
        else if (e.target.closest('.product')) {
            const product = e.target.closest('.product');
            const id = product.dataset.id;
            const category = product.dataset.category;
            
            if (id && category) {
                submitInteractionForm({ productId: parseInt(id), category, action: 'product_click' });
            }
        }
    });
}

/**
 * Fetch and display all products
 */
function fetchAndDisplayRegularProducts() {
    const container = document.getElementById("product-container");
    if (!container) return;
    
    // Show loading state
    container.innerHTML = "<p>Loading products...</p>";

    fetch("addprod.php")
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        container.innerHTML = "";

        if (data.products?.length > 0) {
            const productsHTML = data.products.map(product => generateProductHTML(product)).join('');
            container.innerHTML = productsHTML;
            bindAddToCartEvents(container);
        } else {
            container.innerHTML = "<p>No products available.</p>";
        }
    })
    .catch(err => {
        console.error("Product fetch error:", err);
        container.innerHTML = "<p>Failed to load products. Please try again later.</p>";
    });
}

/**
 * Filter products by category
 * @param {string} category - Category to filter by
 */
function filterProducts(category) {
    if (!category) return;
    
    const products = document.querySelectorAll(".product");
    
    products.forEach(product => {
        const productCategory = product.getAttribute("data-category");
        product.style.display = (category === "all" || productCategory === category) ? "block" : "none";
    });
    
    // Log category filter interaction
    submitInteractionForm({ category, action: 'filter_products' });
}

/**
 * Generates the HTML for a single product
 * @param {Object} product - Product data
 * @returns {string} HTML for the product
 */
function generateProductHTML(product) {
    let extra = "";

    // Category-specific extras
    if (product.category === "phones") {
        extra = `
            <p><strong>Brand:</strong> ${product.brand}</p>
            <p><strong>Specs:</strong> ${product.specs}</p>
        `;
    } else if (product.category === "clothes") {
        extra = `
            <p><strong>Size:</strong> ${product.size}</p>
            <p><strong>Color:</strong> ${product.color}</p>
            <p><strong>Material:</strong> ${product.material}</p>
        `;
    } else if (product.category === "electronics") {
        extra = `
            <p><strong>Brand:</strong> ${product.brand}</p>
            <p><strong>Warranty:</strong> ${product.warranty}</p>
        `;
    } else if (product.category === "others") {
        extra = `
            <p><strong>Category:</strong> ${product.custom_category || 'Miscellaneous'}</p>
            <p><strong>Description:</strong> ${product.description || 'No description provided.'}</p>
        `;
    } else {
        extra = `<p><em>Category: ${product.category}</em></p>`;
    }
// Tag badge if tag exists
let tagHTML = "";
if (product.tag && product.tag.trim() !== "") {
    tagHTML = `<div class="special-tag-badge">${product.tag}</div>`;
}

    return `
        <div class="product ${product.category}" 
             data-id="${product.id}" 
             data-category="${product.category}">
            ${tagHTML}
            <img src="${product.image}" alt="${product.name}">
            <h3>${product.name}</h3>
            <p class="price">ZMW ${product.price}</p>
            ${extra}
            <button class="buy-btn add-to-cart-btn"
                    data-id="${product.id}"
                    data-name="${product.name}"
                    data-price="${product.price}"
                    data-image="${product.image}">
                Add to Cart
            </button>
        </div>
    `;
}


/**
 * Fetch AI-recommended products based on recent user interactions
 * @param {string} eventType - Type of interaction (e.g., 'view', 'click', 'add_to_cart')
 * @param {Object} eventData - Additional event data, such as product_id or category
 */
function fetchAiRecommendedProducts(eventType, eventData = {}) {
    if (!eventType) return;

    const container = document.getElementById("ai-recommendations-container");
    const replyText = document.getElementById("aiReply");

    if (!container || !replyText) return;

    // Show loading feedback
    container.innerHTML = '<p>Loading recommendations...</p>';
    replyText.textContent = 'Analyzing your recent activity...';

    fetch("ai_helper.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
            event_type: eventType,
            event_data: eventData
        })
    })
    .then(res => {
        if (!res.ok) throw new Error(`Server error: ${res.status}`);
        return res.json();
    })
    .then(data => {
        container.innerHTML = ''; // Remove the "Recommended For You" text completely

        if (data.products?.length > 0) {
            const productsHTML = data.products.map(generateProductHTML).join('');
            container.innerHTML += productsHTML;
            replyText.textContent = data.reply || "Here are some suggestions you might like:";
            bindAddToCartEvents(container);
        } else {
            container.innerHTML += "<p>No personalized suggestions yet.</p>";
            replyText.textContent = "No recommendations available yet. Interact with more products!";
        }
    })
    .catch(err => {
        console.error("AI recommendation error:", err);
        container.innerHTML = '<p>Failed to load recommendations.</p>';
        replyText.textContent = "We couldn’t load your recommendations. Please try again later.";
    });
}



// === User Authentication ===

/**
 * Log in user
 * @param {string} username - Username
 * @param {string} password - Password
 * @return {Promise} Promise resolving to login result
 */
function loginUser(username, password) {
    if (!username || !password) {
        displayMessage("Username and password required", "error");
        return Promise.reject(new Error("Missing credentials"));
    }
    
    displayMessage("Logging in...", "info");
    
    return fetch("login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ username, password })
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        if (data.status === "success") {
            sessionStorage.setItem("user_id", data.user_id);
            displayMessage("Login successful!", "success");
            syncCartToDatabase(data.user_id);
            return data;
        } else {
            const errorMsg = data.message || "Unknown error";
            displayMessage("Login failed: " + errorMsg, "error");
            throw new Error(errorMsg);
        }
    })
    .catch(err => {
        console.error("Login error:", err);
        displayMessage("Login failed. Please try again.", "error");
        throw err;
    });
}


/**
 * Sync cart data to database
 * @param {string|number} userId - User ID
 */
function syncCartToDatabase(userId) {
    if (!userId) {
        console.error("Missing user ID for cart sync");
        return Promise.reject(new Error("Missing user ID"));
    }
    
    return fetch("sync_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ user_id: userId, cart: [] }) // Update logic if cart data needed
    })
    .then(res => {
        if (!res.ok) {
            throw new Error(`Server responded with status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        if (data.status === "success") {
            displayMessage("Cart synced successfully!", "success");
            updateCartDisplay();
            updateCartDot();
            return data;
        } else {
            const errorMsg = data.message || "Unknown error";
            displayMessage("Sync failed: " + errorMsg, "error");
            throw new Error(errorMsg);
        }
    })
    .catch(err => {
        console.error("Sync error:", err);
        displayMessage("Failed to sync cart. Please try again.", "error");
        throw err;
    });
}


// === Event Logging ===

// === Initialize on Page Load ===

document.addEventListener("DOMContentLoaded", () => {
    // Initialize main components
    fetchAndDisplayRegularProducts();
    updateCartDisplay();
    updateCartDot();
    fetchAiRecommendedProducts("page_load");
    
    // Set up search form
    initializeSearchForm();
    
    // Set up category filters if they exist
    initializeCategoryFilters();
});

/**
 * Initialize category filter buttons
 */
function initializeCategoryFilters() {
    const filterButtons = document.querySelectorAll("[data-filter]");
    
    filterButtons.forEach(button => {
        button.addEventListener("click", () => {
            const category = button.getAttribute("data-filter");
            if (category) {
                // Update active button styling
                filterButtons.forEach(btn => btn.classList.remove("active"));
                button.classList.add("active");
                
                // Filter products
                filterProducts(category);
            }
        });
    });
}

function recordUserInteraction(productId, category, action) {
    fetch(`get_product_details.php?id=${productId}&category=${category}`)
        .then(response => response.json())
        .then(product => {
            if (!product || product.error) {
                console.error('Error fetching product details:', product.error || 'No product found');
                return;
            }

            // Call main logger with all necessary fields
            logInteraction({
                productId: productId,
                category: category,
                action: action,
                customData: {
                    size: product.size || null,
                    color: product.color || null,
                    material: product.material || null,
                    brand: product.brand || null,
                    specs: product.specs || null,
                    warranty: product.warranty || null,
                    description: product.description || null,
                    custom_category: product.custom_category || null
                }
            });
        })
        .catch(error => {
            console.error('Error fetching product details:', error);
        });
}



// === Add to Cart Buttons ===
document.querySelectorAll('.buy-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        const productId = this.dataset.productId;
        const category = this.dataset.category;

        // Log interaction for analytics
        recordUserInteraction(productId, category, 'add_to_cart');

        // Proceed with actual cart logic here
        // addToCart(productId); // Example
    });
});

// === Record Product View on Load ===
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('product-container');
    if (container) {
        const productId = container.dataset.productId;
        const category = container.dataset.category;

        if (productId && category) {
            recordUserInteraction(productId, category, 'view');
        }
    }
});

// === AI Recommendation Click Logging ===
document.querySelectorAll('.ai-product-card').forEach(card => {
    card.addEventListener('click', function(e) {
        const productId = this.dataset.productId;
        const category = this.dataset.category;

        recordUserInteraction(productId, category, 'ai_rec_click');
    });
});



document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("search-form");
  const input = document.getElementById("search-input");
  const container = document.getElementById("product-container");

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const keyword = input.value.trim();

    if (keyword === "") return;

    fetch("search.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        message: keyword
      })
    })
      .then(response => response.json())
      .then(data => {
        container.innerHTML = "";

        if (data.status === "success" && data.products.length > 0) {
          data.products.forEach(product => {
            container.innerHTML += generateProductHTML(product);
          });

          // Smooth scroll to container after results are rendered
          container.scrollIntoView({ behavior: "smooth" });

        } else {
          container.innerHTML = "<p>No products found.</p>";
        }
      })
      .catch(error => {
        console.error("Search error:", error);
        container.innerHTML = "<p>There was an error processing your request.</p>";
      });
  });
});



function enableImageModal() {
  const modal = document.getElementById("imageModal");
  const modalImg = document.getElementById("modalImage");
  const closeBtn = document.querySelector(".close-btn");

  // Delegated click listener on the whole document
  document.addEventListener("click", function (e) {
    if (e.target.tagName === "IMG") {
      // Check if clicked image is inside either product container
      if (
        e.target.closest("#product-container .product") ||
        e.target.closest("#ai-recommendations-container .ai-product-card")
      ) {
        modal.style.display = "flex";
        modalImg.src = e.target.src;
        document.body.classList.add("modal-open");

        setTimeout(() => {
          modalImg.scrollIntoView({ behavior: "smooth", block: "center" });
        }, 100);
      }
    }
  });

  // Close modal on close button click
  closeBtn.onclick = () => {
    modal.style.display = "none";
    document.body.classList.remove("modal-open");
  };

  // Close modal when clicking outside the image
  modal.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none";
      document.body.classList.remove("modal-open");
    }
  });
}

document.addEventListener("DOMContentLoaded", enableImageModal);
