/**
 * Log user interaction with the site
 * @param {Object} params - Interaction parameters
 * @param {number} [params.userId=1] - User ID (defaults to 1 for anonymous)
 * @param {number|null} params.productId - Product ID (or null)
 * @param {string|null} params.category - Product category (or null)
 * @param {string} params.action - Action performed
 */
function logInteraction({ userId = 1, productId = null, category = null, action }) {
  // Validate required parameters
  if (!action) {
      console.error("Missing required action parameter for interaction logging");
      return;
  }
  
  // Get authenticated user ID from session if available
  const sessionUserId = sessionStorage.getItem("user_id");
  if (sessionUserId) {
      userId = parseInt(sessionUserId);
  }
  
  // Log interaction to server
  fetch('log_interaction.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
          user_id: userId,
          product_id: productId,
          category: category,
          action: action,
          timestamp: new Date().toISOString(),
          page_url: window.location.href,
          referrer: document.referrer || null,
          viewport_width: window.innerWidth,
          viewport_height: window.innerHeight
      })
  })
  .then(response => {
      if (!response.ok) {
          throw new Error(`Failed to log interaction: ${response.status}`);
      }
      return response.text();
  })
  .then(data => {
      console.log("Interaction logged:", action, data);
  })
  .catch(error => {
      console.error("Logging error:", error);
  });

  // For debugging
  if (window.DEBUG_MODE) {
      console.debug("Event logged:", { userId, productId, category, action });
  }
}

/**
* Initialize event tracking on page load
*/
function initializeEventTracking() {
  // Track page view on load
  logInteraction({ 
      action: 'page_view', 
      category: document.body.dataset.pageCategory || null 
  });
  
  // Set up product interaction tracking using event delegation
  setupProductInteractionTracking();
  
  // Track scroll behavior
  setupScrollTracking();
  
  // Track search interactions
  setupSearchTracking();
  
  // Track navigation interactions
  setupNavigationTracking();
  
  // Track session time
  setupSessionTimeTracking();
}

/**
* Set up tracking for product interactions using event delegation
*/
function setupProductInteractionTracking() {
  // Use event delegation on product container for better performance
  const productContainer = document.getElementById('product-container');
  if (!productContainer) return;
  
  productContainer.addEventListener('click', (event) => {
      // Find the closest product element to the clicked element
      const product = event.target.closest('.product');
      if (!product) return;
      
      const productId = parseInt(product.dataset.id);
      const category = product.dataset.category;
      
      // Track add to cart button clicks
      if (event.target.closest('.add-to-cart-btn')) {
          event.stopPropagation(); // Prevent triggering product click event
          logInteraction({ 
              productId, 
              category, 
              action: 'add_to_cart' 
          });
      } 
      // Track product clicks
      else {
          logInteraction({ 
              productId, 
              category, 
              action: 'product_click' 
          });
          
          // Track if this is a recommendation click
          if (product.closest('#ai-recommendations-container')) {
              logInteraction({
                  productId,
                  category,
                  action: 'recommendation_click'
              });
          }
      }
  });
  
  // Same for recommendation containers
  const recContainers = document.querySelectorAll('#generic-recommendation-container, #ai-recommendations-container');
  recContainers.forEach(container => {
      if (!container) return;
      
      container.addEventListener('click', (event) => {
          // Find the closest product element to the clicked element
          const product = event.target.closest('.product');
          if (!product) return;
          
          const productId = parseInt(product.dataset.id);
          const category = product.dataset.category;
          
          // Track add to cart button clicks within recommendations
          if (event.target.closest('.add-to-cart-btn')) {
              event.stopPropagation(); // Prevent triggering product click event
              logInteraction({ 
                  productId, 
                  category, 
                  action: 'recommendation_add_to_cart' 
              });
          } 
          // Track recommendation product clicks
          else {
              logInteraction({ 
                  productId, 
                  category, 
                  action: 'recommendation_click' 
              });
          }
      });
  });
}

/**
* Track user scrolling behavior
*/
function setupScrollTracking() {
  let isScrolling = false;
  let scrollDepth = 0;
  let maxScrollDepth = 0;
  
  // Calculate page scroll percentage
  function getScrollPercentage() {
      const windowHeight = window.innerHeight;
      const documentHeight = Math.max(
          document.body.scrollHeight,
          document.body.offsetHeight,
          document.documentElement.clientHeight,
          document.documentElement.scrollHeight,
          document.documentElement.offsetHeight
      );
      const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      return Math.floor((scrollTop / (documentHeight - windowHeight)) * 100);
  }
  
  // Track scroll events
  window.addEventListener('scroll', () => {
      // Track scroll start
      if (!isScrolling) {
          logInteraction({ 
              action: 'scroll_start',
              category: document.body.dataset.pageCategory || null
          });
          isScrolling = true;
      }
      
      // Track scroll depth
      scrollDepth = getScrollPercentage();
      if (scrollDepth > maxScrollDepth) {
          maxScrollDepth = scrollDepth;
          
          // Log at 25%, 50%, 75% and 100% scroll depth
          if (maxScrollDepth === 25 || maxScrollDepth === 50 || 
              maxScrollDepth === 75 || maxScrollDepth === 100) {
              logInteraction({ 
                  action: 'scroll_depth',
                  category: document.body.dataset.pageCategory || null,
                  productId: null,
                  // Add custom data to the event
                  customData: {
                      depth: maxScrollDepth
                  }
              });
          }
      }
      
      // Debounce scroll end event
      clearTimeout(window.scrollTimeout);
      window.scrollTimeout = setTimeout(() => {
          logInteraction({ 
              action: 'scroll_stop',
              category: document.body.dataset.pageCategory || null,
              // Add custom data to the event
              customData: {
                  final_depth: scrollDepth
              }
          });
          isScrolling = false;
      }, 1000);
  });
}

/**
* Track search interactions
*/
function setupSearchTracking() {
  const searchForm = document.getElementById('search-form');
  if (!searchForm) return;
  
  searchForm.addEventListener('submit', (event) => {
      const searchInput = document.getElementById('search-input');
      if (!searchInput) return;
      
      const query = searchInput.value.trim();
      if (query) {
          logInteraction({ 
              action: 'search_query',
              category: 'search',
              // Add search query as custom data
              customData: {
                  query: query
              }
          });
      }
  });
}

/**
* Track navigation interactions
*/
function setupNavigationTracking() {
  // Track navigation menu clicks
  const navLinks = document.querySelectorAll('nav a, .footer a');
  navLinks.forEach(link => {
      link.addEventListener('click', () => {
          logInteraction({ 
              action: 'navigation_click',
              category: 'navigation',
              // Add link details as custom data
              customData: {
                  link_text: link.textContent.trim(),
                  link_href: link.getAttribute('href')
              }
          });
      });
  });
  
  // Track filter clicks
  const filterButtons = document.querySelectorAll('[data-filter]');
  filterButtons.forEach(button => {
      button.addEventListener('click', () => {
          const category = button.getAttribute('data-filter');
          logInteraction({ 
              action: 'filter_click',
              category: category
          });
      });
  });
}

/**
* Track user session time
*/
function setupSessionTimeTracking() {
  // Record session start time
  const sessionStartTime = new Date();
  
  // Log session start
  logInteraction({ action: 'session_start' });
  
  // Log session duration every minute
  const sessionInterval = setInterval(() => {
      const sessionDuration = Math.floor((new Date() - sessionStartTime) / 1000);
      logInteraction({ 
          action: 'session_heartbeat',
          customData: {
              duration_seconds: sessionDuration
          }
      });
  }, 60000); // Every minute
  
  // Log session end on page unload
  window.addEventListener('beforeunload', () => {
      clearInterval(sessionInterval);
      const sessionDuration = Math.floor((new Date() - sessionStartTime) / 1000);
      
      // Use sendBeacon API for reliable tracking on page unload
      navigator.sendBeacon('log_interaction.php', JSON.stringify({
          user_id: sessionStorage.getItem("user_id") || 1,
          product_id: null,
          category: document.body.dataset.pageCategory || null,
          action: 'session_end',
          timestamp: new Date().toISOString(),
          page_url: window.location.href,
          custom_data: {
              duration_seconds: sessionDuration
          }
      }));
  });
}

// Initialize event tracking when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
  initializeEventTracking();
  
  // Debug flag - set to true to see events in console
  window.DEBUG_MODE = false;
});