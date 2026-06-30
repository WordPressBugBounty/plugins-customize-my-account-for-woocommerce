(function () {
'use strict';

var React = window.React;
var ReactDOM = window.ReactDOM;
var useState = React.useState;
var useEffect = React.useEffect;
var useCallback = React.useCallback;
var useRef = React.useRef;
var data = window.rmaData || {};

/* ---- SVG Icons ---- */
var ICONS = {
grid: '<rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect>',
package: '<line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>',
download: '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line>',
'map-pin': '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle>',
user: '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>',
'log-out': '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line>',
'credit-card': '<rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line>',
circle: '<circle cx="12" cy="12" r="10"></circle>'
};

function Icon(props) {
return React.createElement('svg', {
className: 'rma-nav-icon',
viewBox: '0 0 24 24',
xmlns: 'http://www.w3.org/2000/svg',
dangerouslySetInnerHTML: { __html: ICONS[props.name] || ICONS.circle },
'aria-hidden': 'true'
});
}

/* ---- URL helpers ---- */
function getEndpointFromPath(path, myaccountUrl) {
var base = myaccountUrl ? myaccountUrl.replace(/^https?:\/\/[^\/]+/, '') : '/my-account/';

// Support hash-based sub-navigation: #edit-address/billing
var hash = window.location.hash;
if (hash && hash.indexOf('#') === 0) {
var hashRel = hash.slice(1).replace(/^\//, '').replace(/\/$/, '');
if (hashRel) {
var hashParts = hashRel.split('/');
return { endpoint: hashParts[0], param: hashParts[1] || '' };
}
}

var rel = path.replace(base, '').replace(/^\//, '').replace(/\/$/, '');
if (!rel) return { endpoint: 'dashboard', param: '' };
var parts = rel.split('/');
return { endpoint: parts[0], param: parts[1] || '' };
}

function getActiveKey(path, myaccountUrl) {
return getEndpointFromPath(path, myaccountUrl).endpoint || 'dashboard';
}

/* ---- Safe pushState: use hash for sub-pages to avoid server 404s on refresh ---- */
function safeNavigate(endpoint, param, fullUrl, myaccountUrl) {
var base = myaccountUrl ? myaccountUrl.replace(/^https?:\/\/[^\/]+/, '') : '/my-account/';
var currentPath = window.location.pathname;
var isOnMyAccount = currentPath.indexOf(base.replace(/\/$/, '')) === 0;

if (param) {
// Sub-page (e.g. edit-address/billing): use hash to stay on the my-account URL
var hashTarget = myaccountUrl + '#' + endpoint + '/' + param;
window.history.pushState({ endpoint: endpoint, param: param }, '', hashTarget );
} else {
// Top-level endpoint: safe to pushState to the full URL (it exists on the server)
window.history.pushState({ endpoint: endpoint }, '', fullUrl );
}
window.dispatchEvent(new PopStateEvent('popstate', { state: { endpoint: endpoint, param: param } }));
}

/* ---- AJAX fetch ---- */
function fetchContent(endpoint, param, nonce, ajaxUrl) {
var body = new FormData();
body.append('action', 'rma_load_content');
body.append('nonce', nonce);
body.append('endpoint', endpoint);
body.append('param', param || '');
return fetch(ajaxUrl, { method: 'POST', body: body, credentials: 'same-origin' })
.then(function (r) { return r.json(); })
.then(function (res) {
if (res.success) return res.data.html;
throw new Error('Server error');
});
}

/* ----------------------------------------------------------------
Post-inject initialisation
---------------------------------------------------------------- */
function reinitAfterInject(container, endpoint) {

// Step 1: re-run inline scripts
container.querySelectorAll('script:not([src])').forEach(function (oldScript) {
var newScript = document.createElement('script');
newScript.textContent = oldScript.textContent;
oldScript.parentNode.replaceChild(newScript, oldScript);
});

// Step 2: plugin-specific re-init
setTimeout(function () {

/* -- Chart.js: dashboard spending chart -- */
if (endpoint === 'dashboard') {
var canvas = container.querySelector('#sbCustomerSpendingChart');
if (canvas && typeof window.Chart !== 'undefined' && window.sbChartData) {
var existing = window.Chart.getChart ? window.Chart.getChart(canvas) : null;
if (existing) existing.destroy();

try {
new window.Chart(canvas, {
type: 'line',
data: {
labels: window.sbChartData.labels,
datasets: [{
label: (window.wcmamtxchart && window.wcmamtxchart.amountspent_label) || 'Amount Spent',
data: window.sbChartData.values,
borderWidth: 3,
tension: 0.4,
fill: true
}]
},
options: {
responsive: true,
maintainAspectRatio: false,
plugins: { legend: { display: false } },
scales: { y: { beginAtZero: true } }
}
});
} catch (e) {
console.warn('RMA: Chart init failed', e);
}
}
}

var $ = window.jQuery;

/* -- wcmam order filters -- */
if ($ && endpoint === 'orders') {
var $btns = $(container).find('.wcmam-order-filters button');
if ($btns.length) {
$btns.off('click.rma').on('click.rma', function () {
var filter = $(this).data('filter');
$btns.removeClass('active');
$(this).addClass('active');
if (filter === 'all') {
$(container).find('.wcmam-order-card').fadeIn(200);
} else {
$(container).find('.wcmam-order-card').hide();
$(container).find('.wcmam-order-card[data-status="' + filter + '"]').fadeIn(200);
}
});

$btns.each(function () {
var filter = $(this).data('filter');
if (
filter !== 'all' &&
!$(this).hasClass('wcmam-date-range-btn') &&
!$(container).find('.wcmam-order-card[data-status="' + filter + '"]').length
) {
$(this).addClass('wcmamtx_hidden_order_status');
}
});
}
}

/* -- Intercept edit-address sub-links AND back-links -- */
if (endpoint === 'edit-address' || endpoint.indexOf('edit-address') !== -1) {
container.querySelectorAll('a[href]').forEach(function (link) {
var href = link.getAttribute('href') || '';
var myaccountUrl = (window.rmaData && window.rmaData.myaccountUrl) || '/my-account/';
if (href.indexOf('edit-address') !== -1) {
link.addEventListener('click', function (e) {
e.preventDefault();
var path = href.replace(/^https?:\/\/[^\/]+/, '');
var parsed = getEndpointFromPath(path, myaccountUrl);
safeNavigate(parsed.endpoint, parsed.param, href, myaccountUrl);
});
}
});

var addressForm = container.querySelector('.woocommerce-address-fields') ?
container.querySelector('.woocommerce-address-fields').closest('form') : null;
if (addressForm && !addressForm.dataset.rmaHooked) {
addressForm.dataset.rmaHooked = '1';
addressForm.addEventListener('submit', function (e) {
e.preventDefault();
var myaccountUrl = (window.rmaData && window.rmaData.myaccountUrl) || '/my-account/';

var addressType = 'billing';
var hash = window.location.hash || '';
var hashMatch = hash.match(/edit-address\/([a-z]+)/);
if (hashMatch) addressType = hashMatch[1];

var formData = new FormData(addressForm);
formData.set('action', 'rma_save_address');
formData.set('nonce', (window.rmaData && window.rmaData.nonce) || '');
formData.set('address_type', addressType);

var ajaxUrl = (window.rmaData && window.rmaData.ajaxUrl) || '/wp-admin/admin-ajax.php';

fetch(ajaxUrl, {
method: 'POST',
body: formData,
credentials: 'same-origin'
})
.then(function (r) { return r.json(); })
.then(function (res) {
if (res.success) {
var notices = (res.data && res.data.notices) || [];
var hasError = notices.some(function(n) { return n.type === 'error'; });
if (notices.length) {
var noticeHtml = notices.map(function(n) {
return '<div class="woocommerce-' + n.type + '"><p>' + n.message + '</p></div>';
}).join('');
var wrapper = container.querySelector('.woocommerce-notices-wrapper');
if (!wrapper) {
wrapper = document.createElement('div');
wrapper.className = 'woocommerce-notices-wrapper';
addressForm.parentNode.insertBefore(wrapper, addressForm);
}
wrapper.innerHTML = noticeHtml;
}
if (!hasError) {
safeNavigate('edit-address', '', myaccountUrl + 'edit-address/', myaccountUrl);
}
} else {
console.warn('RMA: address save failed', res);
}
})
.catch(function () {
addressForm.submit();
});
});
}
}

/* -- add-payment-method: mount Stripe UPE after AJAX inject -- */
if (endpoint === 'add-payment-method') {
// Show the payment box (WooCommerce hides it by default with display:none
// via CSS scoped to #add_payment_method #payment .payment_box).
// Since we're inside a React container the CSS selector doesn't match,
// so we force it visible here.
var paymentBox = container.querySelector('.payment_box');
if (paymentBox) {
paymentBox.style.display = 'block';
}

// Mount Stripe UPE elements if available.
// WC Stripe UPE classic stores its elements instance on window after init.
// On initial page load it ran before our container existed, so we must
// create a fresh Stripe Elements instance and mount it ourselves.
var upeParams = window.wc_stripe_upe_params;
var upeEl = container.querySelector('.wc-stripe-upe-element');

if (upeParams && upeEl && typeof Stripe === 'function' && !upeEl.dataset.stripeMounted) {
upeEl.dataset.stripeMounted = '1';

try {
var stripe = Stripe(upeParams.key, { locale: upeParams.locale || 'auto' });

// Build appearance to match the site theme
var appearance = { theme: 'stripe' };

var elementsOptions = {
mode: 'setup',
currency: (upeParams.currency || 'usd').toLowerCase(),
paymentMethodTypes: ['card'],
appearance: appearance
};

// Some older WC Stripe versions use a different Elements init
var elements = stripe.elements(elementsOptions);

var paymentEl = elements.create('payment', {
fields: {
billingDetails: {
email: 'never',
name: 'auto',
phone: 'never',
address: 'never'
}
}
});

paymentEl.mount(upeEl);

// Store on container so the form submit can access them
container._stripeInstance = stripe;
container._stripeElements = elements;
container._stripePaymentEl = paymentEl;

// Handle form submission
var paymentForm = container.querySelector('form#add_payment_method');
if (paymentForm && !paymentForm.dataset.rmaStripeHooked) {
paymentForm.dataset.rmaStripeHooked = '1';
paymentForm.addEventListener('submit', function(e) {
e.preventDefault();

var submitBtn = paymentForm.querySelector('button[type="submit"], input[type="submit"]');
if (submitBtn) {
submitBtn.disabled = true;
var originalText = submitBtn.textContent || submitBtn.value;
submitBtn.textContent = rmaData.addingtext;
submitBtn.value = rmaData.addingtext;
}

function showError(msg) {
if (submitBtn) {
submitBtn.disabled = false;
submitBtn.textContent = originalText;
submitBtn.value = originalText;
}
var errorDiv = paymentForm.querySelector('.woocommerce-error, .wc-stripe-error');
if (!errorDiv) { errorDiv = document.createElement('ul'); errorDiv.className = 'woocommerce-error'; paymentForm.prepend(errorDiv); }
errorDiv.innerHTML = '<li>' + msg + '</li>';
}

var wcAjaxTemplate = upeParams.ajax_url || '';
var initIntentUrl = wcAjaxTemplate.replace('%%endpoint%%', 'wc_stripe_init_setup_intent');

// Step 1: call elements.submit() first (required by Stripe before any async work)
elements.submit()
.then(function(submitResult) {
if (submitResult && submitResult.error) throw submitResult.error;

// Step 2: init a SetupIntent server-side to get client_secret
var formData = new FormData();
formData.append('_ajax_nonce', upeParams.createSetupIntentNonce || '');
formData.append('payment_method_type', 'card');
return fetch(initIntentUrl, { method: 'POST', body: formData, credentials: 'same-origin' })
.then(function(r) { return r.json(); });
})
.then(function(res) {
if (!res.success || !res.data || !res.data.client_secret) {
var msg = (res.data && res.data.error && res.data.error.message) || 'Could not initialize payment.';
throw new Error(msg);
}

// Step 3: confirm the SetupIntent using the Elements
var rma = window.rmaData || {};
var billing = rma.billingAddress || {};
var userEmail = rma.userEmail || null;
return stripe.confirmSetup({
elements: elements,
clientSecret: res.data.client_secret,
confirmParams: {
return_url: upeParams.addPaymentReturnURL || window.location.href,
payment_method_data: {
billing_details: {
email: userEmail,
phone: billing.phone || null,
address: {
country: billing.country || null,
state: billing.state || null,
city: billing.city || null,
postal_code: billing.postal_code || null,
line1: billing.line1 || null,
line2: billing.line2 || null
}
}
}
}
});
})
.then(function(result) {
if (result && result.error) throw result.error;
window.location.href = upeParams.addPaymentReturnURL || (upeParams.myaccountUrl || '/my-account/') + 'payment-methods/';
})
.catch(function(err) {
console.warn('RMA Stripe: payment failed', err);
showError((err && err.message) ? err.message : 'Payment failed. Please try again.');
});
});
}
} catch(e) {
console.warn('RMA: Stripe UPE mount failed', e);
}
}
}

/* -- Custom event for anything else -- */
if ($) $(document).trigger('rma:content-loaded', [container, endpoint]);
container.dispatchEvent(new CustomEvent('rma:content-loaded', {
bubbles: true,
detail: { endpoint: endpoint }
}));

}, 0);
}

/* ---- Skeleton ---- */
function Skeleton() {
return React.createElement('div', { className: 'rma-skeleton' },
React.createElement('div', { className: 'rma-skeleton-line rma-skeleton-title' }),
React.createElement('div', { className: 'rma-skeleton-line' }),
React.createElement('div', { className: 'rma-skeleton-line rma-skeleton-short' }),
React.createElement('div', { className: 'rma-skeleton-line' }),
React.createElement('div', { className: 'rma-skeleton-line rma-skeleton-short' })
);
}

/* ---- Content pane ---- */
function ContentPane(props) {
var contentRef = useRef(null);

useEffect(function () {
if (!contentRef.current || props.html === null) return;
contentRef.current.innerHTML = props.html;
reinitAfterInject(contentRef.current, props.endpoint);
}, [props.html, props.endpoint]);

if (props.loading) {

var $ = window.jQuery;

$('div.rma-content-area').html("");

return React.createElement('div', { className: 'rma-content-area rma-loading' },
React.createElement(Skeleton)
);
}

return React.createElement('div', {
className: 'rma-content-area woocommerce-MyAccount-content',
ref: contentRef
});
}

/* ---- Sidebar ---- */
function Sidebar(props) {
var menuItems = props.menuItems || [];
var activeKey = props.activeKey;
var onNavigate = props.onNavigate;
var userName = data.userName || 'Account';
var userEmail = data.userEmail || '';
var avatarUrl = data.avatarUrl || '';
var mainItems = menuItems.filter(function (i) { return i.key !== 'customer-logout'; });
var logoutItem = menuItems.find(function (i) { return i.key === 'customer-logout'; });

function NavLink(p) {
var item = p.item;
var isActive = activeKey === item.key;
var isLogout = item.key === 'customer-logout';
function handleClick(e) {
if (isLogout) return;
e.preventDefault();
onNavigate(item.key, item.url);
}
return React.createElement('a', {
href: item.url,
className: 'rma-nav-item' + (isActive ? ' rma-active' : '') + (isLogout ? ' rma-logout' : ''),
onClick: handleClick,
'aria-current': isActive ? 'page' : undefined
},
item.iconHtml
? React.createElement('span', { className: 'rma-nav-icon', dangerouslySetInnerHTML: { __html: item.iconHtml } })
: React.createElement(Icon, { name: item.icon }),
React.createElement('span', { className: 'rma-nav-label' }, item.label)
);
}

return React.createElement('nav', {
className: 'rma-sidebar',
role: 'navigation',
'aria-label': 'My account navigation'
},
React.createElement('div', { className: 'rma-user-card' },
data.avatarHtml
? React.createElement('div', { className: 'rma-avatar-widget', dangerouslySetInnerHTML: { __html: data.avatarHtml } })
: (avatarUrl
  ? React.createElement('img', { src: avatarUrl, alt: userName, className: 'rma-avatar', loading: 'lazy' })
  : React.createElement('div', { className: 'rma-avatar rma-avatar-initials' },
    (userName.charAt(0) || 'U').toUpperCase()
    ))
),
React.createElement('ul', { className: 'rma-nav-list', role: 'list' },
mainItems.map(function (item) {
return React.createElement('li', { key: item.key },
React.createElement(NavLink, { item: item })
);
}),
logoutItem && React.createElement('li', { key: 'div', role: 'separator' },
React.createElement('div', { className: 'rma-divider' })
),
logoutItem && React.createElement('li', { key: logoutItem.key },
React.createElement(NavLink, { item: logoutItem })
)
)
);
}

/* ---- Root App ---- */
function App() {
var menuItems = data.menuItems || [];
var ajaxUrl = data.ajaxUrl || '';
var nonce = data.nonce || '';
var myaccountUrl = data.myaccountUrl || '';

var initialPath = data.currentPath || window.location.pathname + window.location.search;
// Also check hash for sub-page state on initial load
var initialHash = window.location.hash;
var initialEndpoint;
if (initialHash && initialHash.length > 1) {
var hashRel = initialHash.slice(1).replace(/^\//, '').replace(/\/$/, '');
var hashParts = hashRel.split('/');
initialEndpoint = { endpoint: hashParts[0] || 'dashboard', param: hashParts[1] || '' };
} else {
initialEndpoint = getEndpointFromPath(initialPath, myaccountUrl);
}

var s1 = useState(getActiveKey(initialPath, myaccountUrl));
var activeKey = s1[0]; var setActiveKey = s1[1];

var s2 = useState(null);
var contentHtml = s2[0]; var setContentHtml = s2[1];

var s3 = useState(false);
var loading = s3[0]; var setLoading = s3[1];

var s4 = useState(null);
var error = s4[0]; var setError = s4[1];

// Track current endpoint so ContentPane knows which re-inits to run
var s5 = useState(initialEndpoint.endpoint);
var currentEndpoint = s5[0]; var setCurrentEndpoint = s5[1];

var loadEndpoint = useCallback(function (endpoint, param) {
setLoading(true);
setError(null);
setCurrentEndpoint(endpoint);
fetchContent(endpoint, param, nonce, ajaxUrl)
.then(function (html) { setContentHtml(html); setLoading(false); })
.catch(function () { setError('Failed to load content. Please try again.'); setLoading(false); });
}, [nonce, ajaxUrl]);

useEffect(function () {
if (!ajaxUrl) return;
loadEndpoint(initialEndpoint.endpoint, initialEndpoint.param);
}, []);

useEffect(function () {
function onPopState() {
var path = window.location.pathname + window.location.search;
var hash = window.location.hash;
var parsed;
if (hash && hash.length > 1) {
var hashRel = hash.slice(1).replace(/^\//, '').replace(/\/$/, '');
var hashParts = hashRel.split('/');
parsed = { endpoint: hashParts[0] || 'dashboard', param: hashParts[1] || '' };
} else {
parsed = getEndpointFromPath(path, myaccountUrl);
}
setActiveKey(parsed.endpoint || 'dashboard');
loadEndpoint(parsed.endpoint || 'dashboard', parsed.param);
}
window.addEventListener('popstate', onPopState);
return function () { window.removeEventListener('popstate', onPopState); };
}, [loadEndpoint, myaccountUrl]);

function handleNavigate(key, url) {
if (key === 'customer-logout') { window.location.href = url; return; }
// add-payment-method requires a real page load so Stripe UPE can fully init
if (key === 'add-payment-method') { window.location.href = url; return; }
setActiveKey(key);
safeNavigate(key, '', url, myaccountUrl);
var parsed = getEndpointFromPath(url.replace(/^https?:\/\/[^\/]+/, ''), myaccountUrl);
loadEndpoint(parsed.endpoint || key, parsed.param);
}

return React.createElement('div', { className: 'rma-layout' },
React.createElement(Sidebar, { menuItems: menuItems, activeKey: activeKey, onNavigate: handleNavigate }),
React.createElement('div', { className: 'rma-main' },
error && React.createElement('div', { className: 'rma-error' }, error),
React.createElement(ContentPane, { html: contentHtml, loading: loading, endpoint: currentEndpoint })
)
);
}

/* ---- Mount ---- */
function mountApp() {
var mount = document.getElementById('rma-root');
if (!mount) return;
mount.innerHTML = '';
if (ReactDOM.createRoot) {
ReactDOM.createRoot(mount).render(React.createElement(App));
} else {
ReactDOM.render(React.createElement(App), mount);
}
}

if (document.readyState === 'loading') {
document.addEventListener('DOMContentLoaded', mountApp);
} else {
mountApp();
}

}());