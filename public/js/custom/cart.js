// cart.js

// Function to add a product to the cart
function addToCart(productId) {
    // Make an AJAX request to add the product to the cart
    $.ajax({
        url: '/cart/add',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            product_id: productId,
            user_id: 1
        },
        success: function(response) {
            // Display success message
            alert(response.success);
            console.log($('meta[name="csrf-token"]').attr('content'));
            console.log('Product ID:', productId);


            // Reload the page to update the cart
            location.reload();
        },
        error: function(xhr, status, error) {
            // Display error message
            console.error(xhr.responseText);
            alert('An error occurred while adding the product to the cart.');
        }
    });
}

// Function to remove a product from the cart
function removeFromCart(productId,userId) {
    // Make an AJAX request to remove the product from the cart
    $.ajax({
        url: '/cart/remove',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            product_id: productId,
            user_id: userId
        },
        success: function(response) {
            // Optionally, you can update the cart count or remove the corresponding row from the table
            alert(response.success); // Display success message
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText); // Log error message
            alert('An error occurred while removing the product from the cart.');
        }
    });
}

// Handle form submission
function handleFormSubmission() {
    $('form').submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting normally

        // Check if the PayPal checkbox is checked
        if ($('#Paypal-1').is(':checked')) {
            // Perform AJAX request to insert order data into the database
            $.ajax({
                url: '/place-order',
                method: 'POST',
                data: $(this).serialize(), // Serialize form data
                success: function(response) {
                    // Redirect the user to PayPal sandbox URL with necessary parameters
                    window.location.href = response.redirectUrl;
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('An error occurred while placing the order.');
                }
            });
        } else {
            // Handle other payment methods or display an error message
            alert('Please select a payment method.');
        }
    });
}

// Call the function to initialize the form submission handling
//handleFormSubmission();



// Function to clear the cart
function clearCart() {
    // Make an AJAX request to clear the cart
    $.ajax({
        url: '/cart/clear',
        method: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // Display success message
            alert(response.success);

            // Reload the page to update the cart
            location.reload();
        },
        error: function(xhr, status, error) {
            // Display error message
            console.error(xhr.responseText);
            alert('An error occurred while clearing the cart.');
        }
    });
}
