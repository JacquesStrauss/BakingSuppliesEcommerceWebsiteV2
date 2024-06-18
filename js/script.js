//----------------------------- LOG IN/OUT AND INITIALIZATION -------------------------------------- 
// Function to check if the user is logged in
async function checkAuthentication() {
    try {
        const response = await fetch('php/auth.php');
        if (!response.ok) {
            throw new Error('Failed to fetch authentication status');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error checking authentication:', error);
        return { isLoggedIn: false };
    }
}

// Function to handle page initialization
function initializePage() {
    const isLoggedIn = document.cookie.includes('isLoggedIn=true');
    const loginTab = document.getElementById('loginTab');
    const signupTab = document.getElementById('signupTab');
    const logoutTab = document.getElementById('logoutTab');

    if (isLoggedIn) {
        loginTab.style.display = 'none';
        signupTab.style.display = 'none';
    } else {
     //   logoutTab.style.display = 'none';
    }
}

// Function To handle log in
function loginUser(event) {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    fetch('php/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email: email, password: password })
    }).then(response => response.json())
      .then(data => {
          const loginMessage = document.getElementById('login-message');
          console.log('Login response data:', data);  // Debug log
          if (data.status === 'success') {
              loginMessage.innerText = 'Login successful';
              document.cookie = "isLoggedIn=true; path=/"; // Set a cookie to indicate login status
              if (data.is_admin) {
                  console.log('Redirecting to admin page');  // Debug log
                  window.location.href = 'admin/index.html';
              } else {
                  console.log('Redirecting to index page');  // Debug log
                  window.location.href = 'index.html';
              }
          } else {
              loginMessage.innerText = 'Login failed: ' + data.message;
          }
      }).catch(error => {
          console.error('Error:', error);  // Debug log for any fetch errors
      });
}

// Function to handle logout
function logout() {
    window.location.href = 'php/logout.php';
}


//--------------------------------------------------------------------------------------------------



//--------------------------------------FETCH AND DISPLAY PRODUCTS (THESE GO HAND IN HAND ) ---------------------------
// Function to fetch and display products
async function fetchProducts() {
    try {
        const response = await fetch('php/getProducts.php'); // PHP script to get product data
        const products = await response.json();
        displayProducts(products);
    } catch (error) {
        console.error('Error fetching products:', error);
    }
}

// Function to display products
 function displayProducts(products) {
    const productsContainer = document.getElementById('products');
    productsContainer.innerHTML = '';

    products.forEach(product => {
        const productElement = document.createElement('div');
        productElement.classList.add('product');

        const productImage = document.createElement('img');
        productImage.src = product.image_url; // Assuming product data includes image_url property
        productImage.alt = product.name;

        const productName = document.createElement('h3');
        productName.textContent = product.name;

        const productPrice = document.createElement('p');
        productPrice.textContent = `$${product.price.toFixed(2)}`;
        productName.onclick = () => {
            window.location.href = `productdetail.html?id=${product.id}`;
        };

        productElement.appendChild(productImage);
        productElement.appendChild(productName);
        productElement.appendChild(productPrice);
        productsContainer.appendChild(productElement);
    });
}


//--------------------------------------------------------------------------------------------------



//--------------------------------------FETCH AND DISPLAY CATEGORIES (THESE GO HAND IN HAND ) ---------------------------
// Function to fetch and display categories
async function fetchCategories() {
    try {
        const response = await fetch('php/getCategories.php');
        const categories = await response.json();
        displayCategories(categories);
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
}

// Function to display categories
function displayCategories(categories) {
    const categoriesContainer = document.getElementById('categories');
    categoriesContainer.innerHTML = '';

    categories.forEach(category => {
        const categoryElement = document.createElement('div');
        categoryElement.classList.add('category');
        
        const categoryName = document.createElement('h3');
        categoryName.textContent = category.category_name;
        categoryName.onclick = () => {
            window.location.href = `category.html?category_id=${category.category_id}`;
        };

        categoryElement.appendChild(categoryName);
        categoriesContainer.appendChild(categoryElement);
    });
}


//--------------------------------------------------------------------------------------------------




function loadProducts() {
    fetch('php/getProducts.php')
        .then(response => response.json())
        .then(products => {
            const productsDiv = document.getElementById('products');
            products.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.className = 'product';
                productDiv.innerHTML = `
                    <h3>${product.name}</h3>
                    <p>${product.description}</p>
                    <p>$${product.price}</p>
                    <button onclick="addToCart(${product.id})">Add to Cart</button>
                `;
                productsDiv.appendChild(productDiv);
            });
        });
}

async function fetchProductDetail(productId) {
    try {
        const response = await fetch(`php/getProductDetail.php?product_id=${productId}`);
        const product = await response.json();
        displayProductDetail(product);
    } catch (error) {
        console.error('Error fetching product details:', error);
    }
}

function displayProductDetail(product) {
    const productDetailContainer = document.getElementById('product-detail');
    productDetailContainer.innerHTML = `
        <h2>${product.name}</h2>
        <img src="${product.image_url}" alt="${product.name}">
        <p>${product.description}</p>
        <p>Price: $${product.price.toFixed(2)}</p>
    `;
}

function addToCart() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('product_id');
    const quantity = document.getElementById('quantity').value;
    // Add logic to add product to cart
    const message = document.getElementById('add-to-cart-message');
    message.innerText = `Added ${quantity} of this product to your cart.`;
}



function loadProductDetails() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    fetch(`php/getProductDetail.php?id=${productId}`)
        .then(response => response.json())
        .then(product => {
            const productDetailsDiv = document.getElementById('product-details');
            productDetailsDiv.innerHTML = `
                <h3>${product.name}</h3>
                <p>${product.description}</p>
                <p>$${product.price}</p>
                <button onclick="addToCart(${product.id})">Add to Cart</button>
            `;
        });
}

function loadCartItems() {
    fetch('php/getCartItems.php')
        .then(response => response.json())
        .then(cartItems => {
            const cartItemsDiv = document.getElementById('cart-items');
            cartItemsDiv.innerHTML = '';
            cartItems.forEach(item => {
                const cartItemDiv = document.createElement('div');
                cartItemDiv.className = 'cart-item';
                cartItemDiv.innerHTML = `
                    <h3>${item.name}</h3>
                    <p>Quantity: ${item.quantity}</p>
                    <p>$${item.price}</p>
                `;
                cartItemsDiv.appendChild(cartItemDiv);
            });
        });
}

function addToCart(productId) {
    fetch('php/addToCart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ productId: productId })
    }).then(response => {
        if (response.ok) {
            alert('Product added to cart');
        } else {
            alert('Error adding product to cart');
        }
    });
}



function addProduct(event) {
    event.preventDefault();
    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    const price = document.getElementById('price').value;
    fetch('../php/addProduct.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ name: name, description: description, price: price })
    }).then(response => response.json())
      .then(data => {
          const addProductMessage = document.getElementById('add-product-message');
          if (data.status === 'success') {
              addProductMessage.innerText = 'Product added successfully.';
          } else {
              addProductMessage.innerText = 'Failed to add product: ' + data.message;
          }
      });
}

function loadAdminProducts() {
    fetch('../php/getProductsAdmin.php')
        .then(response => response.json())
        .then(products => {
            const adminProductsDiv = document.getElementById('admin-products');
            products.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.className = 'product';
                productDiv.innerHTML = `
                    <h3>${product.name}</h3>
                    <p>${product.description}</p>
                    <p>$${product.price}</p>
                    <button onclick="editProductPage(${product.id})">Edit</button>
                    <button onclick="deleteProduct(${product.id})">Delete</button>
                `;
                adminProductsDiv.appendChild(productDiv);
            });
        });
}

function editProductPage(productId) {
    window.location.href = `editProduct.html?id=${productId}`;
}

function loadProductToEdit() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');
    fetch(`../php/getProductById.php?id=${productId}`)
        .then(response => response.json())
        .then(product => {
            document.getElementById('product-id').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('description').value = product.description;
            document.getElementById('price').value = product.price;
        });
}

function editProduct(event) {
    event.preventDefault();
    const id = document.getElementById('product-id').value;
    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    const price = document.getElementById('price').value;
    fetch('../php/editProduct.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id, name: name, description: description, price: price })
    }).then(response => response.json())
      .then(data => {
          const editProductMessage = document.getElementById('edit-product-message');
          if (data.status === 'success') {
              editProductMessage.innerText = 'Product updated successfully.';
          } else {
              editProductMessage.innerText = 'Failed to update product: ' + data.message;
          }
      });
}

function deleteProduct(productId) {
    fetch(`../php/deleteProduct.php?id=${productId}`, {
        method: 'GET'
    }).then(response => response.json())
      .then(data => {
          if (data.status === 'success') {
              loadAdminProducts();
          } else {
              alert('Failed to delete product: ' + data.message);
          }
      });
}

//FETCH IMAGE URLS

function getImageUrl() {
    var areaCode = document.getElementById("category").value;

    fetch("getImage.php", {
        method: "POST",
        body: new URLSearchParams({ areaCode: areaCode }),
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        }
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById("servicesTable").innerHTML = data;
    })
    .catch(error => {
        console.error('Error:', error);
    });
}




// Main function to be executed when the page loads
async function main() {
    initializePage();
    await fetchCategories();
    await fetchProducts();
}



document.addEventListener('DOMContentLoaded', main);
document.addEventListener('DOMContentLoaded', () => {
    // Attach event listener to the form
    document.getElementById('login-form').addEventListener('submit', loginUser);
});