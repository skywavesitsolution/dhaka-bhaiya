<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<!-- Form to capture product ID -->
<form id="myForm">
    <input type="text" name="product_id" id="product_id" placeholder="Enter Product ID">
    <button type="submit">Submit</button>
</form>

<!-- Placeholder for displaying the response message -->
<div id="responseMessage"></div>

<script>
    // Event listener for form submission
    document.getElementById('myForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevents the form from submitting traditionally
        
        // Get the product ID entered by the user
        var productId = document.getElementById('product_id').value;

        // Check if the product ID is entered
        if (!productId) {
            document.getElementById('responseMessage').innerHTML = `<p>Please enter a product ID.</p>`;
            return;
        }

        // Construct the URL with product ID as a path parameter
        var url = `/LatifTraders/productajax/${encodeURIComponent(productId)}`;

        // Perform AJAX request using Fetch API
        fetch(url, {
            method: 'GET',  // Use GET method to send data via URL
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for Laravel
            }
        })
        .then(response => response.json()) // Parse the JSON response
        .then(response => {
            // Check the response status
            if (response.status === 'success') {
                // Display the product details
                document.getElementById('responseMessage').innerHTML = `
                    <h3>Product Details</h3>
                    <p><strong>Product Name:</strong> ${response.product_name}</p>
                    <p><strong>Product Price:</strong> $${response.product_price}</p>
                    <p><strong>Description:</strong> ${response.product_description}</p>
                `;
            } else {
                // Handle error if product not found
                document.getElementById('responseMessage').innerHTML = `<p>Product not found.</p>`;
            }
        })
        .catch(error => {
            // Handle any errors in the request
            console.error('Error:', error);
            document.getElementById('responseMessage').innerHTML = `<p>There was an error fetching the product data.</p>`;
        });
    });
</script>

</body>
</html>
