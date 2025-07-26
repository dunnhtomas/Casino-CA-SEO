// Casino Portal - Interactive JavaScript
// Part of the Enterprise Casino Portal PRD implementation

document.addEventListener('DOMContentLoaded', async function() {
    console.log('🎰 Casino Authority Portal - Frontend Active');
    
    // Initialize the application
    await initializeApp();
    
    // Set up event listeners
    setupEventListeners();
    
    // Load dynamic content
    loadDynamicContent();
});

/**
 * Initialize the application
 */
async function initializeApp() {
    try {
        // Check server status
        const statusResponse = await fetch('/api/status');
        const status = await statusResponse.json();
        
        console.log('Server Status:', status);
        
        // Update UI with live status
        updateStatusIndicators(status);
        
    } catch (error) {
        console.error('Failed to initialize app:', error);
        showErrorMessage('Failed to connect to server');
    }
}

/**
 * Set up event listeners
 */
function setupEventListeners() {
    // Add click handlers for casino cards
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-casino-id]')) {
            const casinoId = e.target.getAttribute('data-casino-id');
            handleCasinoClick(casinoId);
        }
        
        if (e.target.matches('[data-action="load-more"]')) {
            e.preventDefault();
            loadMoreCasinos();
        }
    });
    
    // Add search functionality
    const searchInput = document.querySelector('#casino-search');
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }
}

/**
 * Load dynamic content
 */
async function loadDynamicContent() {
    try {
        // Load top casinos
        const casinosResponse = await fetch('/api/casinos');
        const casinosData = await casinosResponse.json();
        
        if (casinosData.success) {
            renderCasinoList(casinosData.data);
        }
        
        // Load categories
        const categoriesResponse = await fetch('/api/categories');
        const categoriesData = await categoriesResponse.json();
        
        if (categoriesData.success) {
            renderCategories(categoriesData.data);
        }
        
    } catch (error) {
        console.error('Failed to load dynamic content:', error);
    }
}

/**
 * Update status indicators
 */
function updateStatusIndicators(status) {
    // Update the development indicator
    const devIndicator = document.querySelector('.dev-indicator');
    if (devIndicator) {
        devIndicator.textContent = `${status.environment.toUpperCase()} - ${status.status.toUpperCase()}`;
    }
    
    // Update any status displays in the UI
    const statusElements = document.querySelectorAll('[data-status]');
    statusElements.forEach(el => {
        const statusType = el.getAttribute('data-status');
        if (status.features && status.features[statusType]) {
            el.classList.add('status-active');
            el.textContent = `${statusType}: ${status.features[statusType]}`;
        }
    });
}

/**
 * Render casino list
 */
function renderCasinoList(casinos) {
    const container = document.querySelector('#casino-list');
    if (!container) return;
    
    const html = casinos.map(casino => `
        <div class="casino-card" data-casino-id="${casino.id}">
            <div class="casino-header">
                <div class="casino-logo">
                    <span class="casino-emoji">🎰</span>
                    <h3>${casino.name}</h3>
                </div>
                <div class="casino-rating">
                    <span class="stars">${generateStars(casino.rating)}</span>
                    <span class="rating-number">${casino.rating}/5</span>
                </div>
            </div>
            
            <div class="casino-details">
                <div class="detail-item">
                    <span class="label">Established:</span>
                    <span class="value">${casino.established}</span>
                </div>
                <div class="detail-item">
                    <span class="label">RTP:</span>
                    <span class="value">${casino.rtp}%</span>
                </div>
                <div class="detail-item">
                    <span class="label">Payout:</span>
                    <span class="value">${casino.payoutSpeed}</span>
                </div>
                <div class="detail-item">
                    <span class="label">Games:</span>
                    <span class="value">${casino.gamesCount}</span>
                </div>
            </div>
            
            <div class="casino-bonus">
                <strong>${casino.bonus}</strong>
            </div>
            
            <div class="casino-pros-cons">
                <div class="pros">
                    <h4>✅ Pros:</h4>
                    <ul>
                        ${casino.pros.map(pro => `<li>${pro}</li>`).join('')}
                    </ul>
                </div>
                <div class="cons">
                    <h4>❌ Cons:</h4>
                    <ul>
                        ${casino.cons.map(con => `<li>${con}</li>`).join('')}
                    </ul>
                </div>
            </div>
            
            <div class="casino-actions">
                <button class="btn btn-primary" onclick="visitCasino('${casino.slug}')">
                    Get Bonus
                </button>
                <button class="btn btn-secondary" onclick="viewReview('${casino.slug}')">
                    More Info
                </button>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = html;
}

/**
 * Render categories
 */
function renderCategories(categories) {
    const container = document.querySelector('#categories-list');
    if (!container) return;
    
    const html = categories.map(category => `
        <div class="category-card" data-category="${category.id}">
            <div class="category-icon">${category.icon}</div>
            <div class="category-content">
                <h3>${category.name}</h3>
                <div class="category-winner">
                    <strong>${category.topCasino}</strong>
                    <div class="category-stats">
                        <span>RTP: ${category.rtp}</span>
                        <span>Games: ${category.games}</span>
                        <span>Rating: ${category.rating}</span>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = html;
}

/**
 * Generate star rating HTML
 */
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    let stars = '';
    
    // Full stars
    for (let i = 0; i < fullStars; i++) {
        stars += '⭐';
    }
    
    // Half star
    if (hasHalfStar) {
        stars += '⭐'; // Using full star for simplicity
    }
    
    // Empty stars
    for (let i = 0; i < emptyStars; i++) {
        stars += '☆';
    }
    
    return stars;
}

/**
 * Handle casino card click
 */
function handleCasinoClick(casinoId) {
    console.log('Casino clicked:', casinoId);
    // Add click tracking or navigation logic here
}

/**
 * Handle search input
 */
async function handleSearch(query) {
    if (query.length < 2) return;
    
    try {
        const response = await fetch(`/api/search?q=${encodeURIComponent(query)}`);
        const data = await response.json();
        
        if (data.success) {
            renderSearchResults(data.data);
        }
    } catch (error) {
        console.error('Search failed:', error);
    }
}

/**
 * Visit casino (affiliate link)
 */
function visitCasino(slug) {
    console.log('Visiting casino:', slug);
    // Track click for analytics
    trackEvent('casino_visit', { casino: slug });
    
    // In production, this would redirect to affiliate URL
    alert(`Redirecting to ${slug} casino...`);
}

/**
 * View casino review
 */
function viewReview(slug) {
    console.log('Viewing review for:', slug);
    // Navigate to review page
    window.location.href = `/reviews/${slug}`;
}

/**
 * Load more casinos (pagination)
 */
async function loadMoreCasinos() {
    console.log('Loading more casinos...');
    // Implement pagination logic
}

/**
 * Track events for analytics
 */
function trackEvent(eventName, eventData) {
    console.log('Event tracked:', eventName, eventData);
    // In production, send to analytics service
}

/**
 * Show error message
 */
function showErrorMessage(message) {
    console.error('Error:', message);
    // Show user-friendly error message
}

/**
 * Debounce function for search
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Export functions for global access
window.CasinoPortal = {
    visitCasino,
    viewReview,
    trackEvent
};

console.log('🚀 Casino Portal JavaScript loaded successfully');
