<?php
include("include/connect.php");
// Rest of your code for product insertion...
// Check for messages after deletion
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $messageType = $_SESSION['message_type'];
    
    // Clear the message after displaying
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

function sanitizeInput($input) {
    global $con;
    return mysqli_real_escape_string($con, trim($input));
}

if (isset($_POST['ins'])) {
    // Validation
    $errors = [];

    // Sanitize inputs
    $pname = sanitizeInput($_POST['name']);
    $category = sanitizeInput($_POST['category']);
    $description = sanitizeInput($_POST['description']);
    $quantity = filter_var($_POST['quantity'], FILTER_VALIDATE_INT);
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $brand = sanitizeInput($_POST['brand']);

    // Image handling
    $image = $_FILES['photo']['name'];
    $temp_image = $_FILES['photo']['tmp_name'];
    $image_error = $_FILES['photo']['error'];

    // Validation checks
    if (empty($pname)) $errors[] = "Product name is required";
    if ($category == "all") $errors[] = "Please select a valid category";
    if ($quantity === false || $quantity < 0) $errors[] = "Invalid quantity";
    if ($price === false || $price < 0) $errors[] = "Invalid price";
    if (empty($image)) $errors[] = "Product image is required";

    // Image upload validation
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_file_size = 5 * 1024 * 1024; // 5MB
    $file_type = mime_content_type($temp_image);
    $file_size = $_FILES['photo']['size'];

    if (!in_array($file_type, $allowed_types)) {
        $errors[] = "Invalid image type. Only JPEG, PNG, and GIF are allowed.";
    }
    if ($file_size > $max_file_size) {
        $errors[] = "Image size exceeds 5MB limit.";
    }

    // If no errors, proceed with insertion
    if (empty($errors)) {
        // Generate unique filename to prevent overwriting
        $unique_filename = uniqid() . '_' . $image;
        $upload_path = "product_images/" . $unique_filename;

        // Set timezone
        date_default_timezone_set('Asia/Manila');
        $date_created = date('Y-m-d H:i:s');

        // Move uploaded file
        if (move_uploaded_file($temp_image, $upload_path)) {
            // Prepare SQL query with prepared statement
            $query = "INSERT INTO `products` 
                      (pname, category, description, price, qtyavail, img, brand, date_created) 
                      VALUES 
                      (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = mysqli_prepare($con, $query);
            mysqli_stmt_bind_param($stmt, "sssdisss", 
                $pname, $category, $description, $price, 
                $quantity, $unique_filename, $brand, $date_created
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success_message'] = "Product successfully added!";
                header("Location: inventory.php");
                exit();
            } else {
                $errors[] = "Database insertion failed: " . mysqli_error($con);
            }
        } else {
            $errors[] = "File upload failed.";
        }
    }

    // If there are errors, store them in session and redirect
    if (!empty($errors)) {
        $_SESSION['error_messages'] = $errors;
        header("Location: inventory.php");
        exit();
    }
}

// In your inventory.php, add error and success message display
if (isset($_SESSION['error_messages'])) {
    foreach ($_SESSION['error_messages'] as $error) {
        echo "<div class='error-message'>$error</div>";
    }
    unset($_SESSION['error_messages']);
}

if (isset($_SESSION['success_message'])) {
    echo "<div class='success-message'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}

if (isset($_GET['pid'])) {
    // Sanitize the input to prevent SQL injection
    $id = mysqli_real_escape_string($con, $_GET['pid']);
    
    // Optional: Check if the product exists before deletion
    $checkQuery = "SELECT * FROM products WHERE pid = '$id'";
    $checkResult = mysqli_query($con, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        // Product exists, proceed with deletion
        $query = "DELETE FROM products WHERE pid = '$id'";
        
        if (mysqli_query($con, $query)) {
            // Successful deletion
            $_SESSION['message'] = "Product successfully deleted.";
            $_SESSION['message_type'] = "success";
        } else {
            // Deletion failed
            $_SESSION['message'] = "Error deleting product: " . mysqli_error($con);
            $_SESSION['message_type'] = "error";
        }
    } else {
        // Product not found
        $_SESSION['message'] = "Product not found.";
        $_SESSION['message_type'] = "error";
    }
    
    // Redirect back to inventory page
    header("Location: inventory.php");
    exit();
}

if (isset($_POST['submitt'])) {
	$pname = $_POST['name1'];
	$category = $_POST['category1'];
	$description = $_POST['description1'];
	$quantity = $_POST['quantity1'];
	$price = $_POST['price1'];
	$brand = $_POST['brand1'];
	$image = $_FILES['photo1']['name'];
	$temp_image = $_FILES['photo1']['tmp_name'];
	$pid2 = $_POST['pid1'];
	$image2 = $_POST['prevphoto'];
	$prevcat = $_POST['prev'];
	if ($category == "all") {
		$category = $prevcat;
	}

	if (!empty($image))
		move_uploaded_file($temp_image, "product_images/$image");

	if (!empty($image))
		$query = "Update `products` set pname = '$pname', category = '$category', description = '$description', qtyavail = $quantity, brand ='$brand', price = $price, img ='$image' where pid = $pid2";
	else
		$query = "Update `products` set pname = '$pname', category = '$category', description = '$description', qtyavail = $quantity, brand ='$brand', price = $price, img ='$image2' where pid = $pid2";

	$result = mysqli_query($con, $query);

	if ($result) {
		echo "<script> alert('Successfully updated product') </script>";
	}
}

if (isset($_GET['odd'])) {
	$oid = $_GET['odd'];

	$query = "UPDATE orders set datedel = CURDATE() where oid = $oid";

	$result = mysqli_query($con, $query);

	header("Location: inventory.php");
	exit();
}

?>
<?php if (isset($message)): ?>
    <div class='
        fixed top-5 right-5 z-50 px-4 py-2 rounded 
        <?php echo $messageType == "success" ? "bg-green-500" : "bg-red-500"; ?> 
        text-white
    '>
        <?php echo htmlspecialchars($message); ?>
    </div>
    
    <script>
        // Automatically remove the message after 3 seconds
        setTimeout(() => {
            const messageEl = document.querySelector('.fixed.top-5.right-5');
            if (messageEl) {
                messageEl.remove();
            }
        }, 3000);
    </script>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-5deg); }
        75% { transform: rotate(5deg); }
    }

    @keyframes bounce-slow {
        0%, 100% { transform: translateY(-25%); animation-timing-function: cubic-bezier(0.8, 0, 1, 1); }
        50% { transform: translateY(0); animation-timing-function: cubic-bezier(0, 0, 0.2, 1); }
    }

    .animate-wiggle {
        animation: wiggle 0.3s ease-in-out;
    }

    .animate-bounce-slow {
        animation: bounce-slow 1s infinite;
    }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="index.php" class="flex items-center">
                <img src="img/lg.png" alt="MyTechPC Logo" class="h-10 w-auto">
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Inventory Management</h1>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container mx-auto px-4 py-8 grid md:grid-cols-3 gap-6">
        <!-- Product Insertion Form -->
        <div class="md:col-span-1 bg-white shadow-lg rounded-lg p-6 animate-fade-in">
            <h2 class="text-xl font-semibold mb-6 text-gray-800">Add New Product</h2>
            <form 
                action="inventory.php" 
                method="post" 
                enctype="multipart/form-data" 
                class="space-y-4"
            >
                <!-- Product Name -->
                <div>
                    <label class="block text-gray-700 mb-2">Product Name</label>
                    <input 
                        type="text" 
                        name="name" 
                        required 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-gray-700 mb-2">Category</label>
                    <select 
                        name="category" 
                        required 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Select Category</option>
                        <option value="set">Pre-Built</option>
                        <option value="keyboard">Keyboard</option>
                        <option value="mouse">Mouse</option>
                        <option value="headset">Headset</option>
                        <option value="motherboard">Motherboard</option>
                        <option value="chassis">Chassis</option>
                        <option value="Powersupply">Power Supply</option>
                        <option value="coolingfan">Cooling Fan</option>
                        <option value="cpu">CPU</option>
                        <option value="gpu">GPU</option>
                        <option value="ram">RAM</option>
                    </select>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-gray-700 mb-2">Description</label>
                    <textarea 
                        name="description" 
                        required 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    ></textarea>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-gray-700 mb-2">Price</label>
                    <input 
                        type="number" 
                        name="price" 
                        required 
                        min="0" 
                        step="0.01" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-gray-700 mb-2">Quantity</label>
                    <input 
                        type="number" 
                        name="quantity" 
                        required 
                        min="0" 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Image Upload -->
                <div>
                    <label class="block text-gray-700 mb-2">Product Image</label>
                    <input 
                        type="file" 
                        name="photo" 
                        required 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Brand -->
                <div>
                    <label class="block text-gray-700 mb-2">Brand</label>
                    <input 
                        type="text" 
                        name="brand" 
                        required 
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    name="ins" 
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300"
                >
                    Add Product
                </button>
            </form>
        </div>

        <!-- Product Search and Listing -->
        <div class="md:col-span-2 space-y-6">
            <!-- Search Section -->
            <div class="bg-white shadow-lg rounded-lg p-6 animate-fade-in">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Search Products</h2>
                <form action="inventory.php" method="post" class="flex space-x-4">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search products..." 
                        class="flex-grow px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <select 
                        name="cat" 
                        class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="all">All Categories</option>
                        <option value="set">Pre-Built</option>
                        <option value="keyboard">Keyboard</option>
                        <option value="mouse">Mouse</option>
                        <option value="headset">Headset</option>
                        <option value="motherboard">Motherboard</option>
                        <option value="chassis">Chassis</option>
                        <option value="Powersupply">Power Supply</option>
                        <option value="coolingfan">Cooling Fan</option>
                        <option value="cpu">CPU</option>
                        <option value="gpu">GPU</option>
                        <option value="ram">RAM</option>
                    </select>
                    <button 
                        type="submitt" 
                        name="search1" 
                        class="bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition duration-300"
                    >
                        Search
                    </button>
                </form>
            </div>

            <?php
                        if (isset($_GET['pidd'])) {
                            $id = $_GET['pidd'];
                        
                            $stmt = $con->prepare("SELECT * FROM products WHERE pid = ?");
                            $stmt->bind_param("i", $id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        
                            if ($result && $result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                        echo 
                        "<div 
                        id='editProductModal' 
                        class='fixed inset-0 z-50 hidden items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-black bg-opacity-50'
                    >
                        <div 
                            class='relative w-full max-w-2xl mx-auto my-6 transform transition-all duration-300 ease-in-out scale-95 opacity-0'
                            id='modalContent'
                        >
                            <div class='bg-white rounded-lg shadow-xl overflow-hidden'>
                                <!-- Modal Header -->
                                <div class='flex items-center justify-between p-5 border-b border-gray-200'>
                                    <h3 class='text-2xl font-semibold text-gray-900'>Edit Product</h3>
                                    <button 
                                        onclick='closeModal()'
                                        class='text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center'
                                    >
                                        <svg class='w-5 h-5' fill='currentColor' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'>
                                            <path fill-rule='evenodd' d='M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z' clip-rule='evenodd'></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Modal Body -->
                                <form 
                                    id='editProductForm'
                                    action='inventory.php'
                                    method='post'
                                    enctype='multipart/form-data'
                                    class='p-6 space-y-6'
                                >
                                    <!-- Hidden Inputs -->
                                    <input 
                                        type='hidden' 
                                        name='pid1'
                                        id='editPid'
                                    >
                                    <input 
                                        type='hidden' 
                                        name='prevphoto' 
                                        id='editPrevPhoto'
                                    >
                                    <input 
                                        type='hidden' 
                                        name='prev' 
                                        id='editPrevCategory'
                                    >

                                    <!-- Form Grid -->
                                    <div class='grid grid-cols-1 md:grid-cols-2 gap-6'>
                                        <!-- Product Name -->
                                        <div>
                                            <label class='block text-sm font-medium text-gray-700 mb-2'>Product Name</label>
                                            <input 
                                                type='text' 
                                                name='name1' 
                                                id='editProductName'
                                                required 
                                                class='w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
                                            >
                                        </div>

                                        <!-- Category -->
                                        <div>
                                            <label class='block text-sm font-medium text-gray-700 mb-2'>Category</label>
                                            <select 
                                                name='category1' 
                                                id='editProductCategory'
                                                required 
                                                class='w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
                                            >
                                                <option value='set'>Pre-Built</option>
                                                <option value='keyboard'>Keyboard</option>
                                                <option value='mouse'>Mouse</option>
                                                <option value='headset'>Headset</option>
                                                <option value='motherboard'>Motherboard</option>
                                                <option value='chassis'>Chassis</option>
                                                <option value='Powersupply'>Power Supply</option>
                                                <option value='coolingfan'>Cooling Fan</option>
                                                <option value='cpu'>CPU</option>
                                                <option value='gpu'>GPU</option>
                                                <option value='ram'>RAM</option>
                                            </select>
                                        </div>

                                        <!-- Description -->
                                        <div class='md:col-span-2'>
                                            <label class='block text-sm font-medium text-gray-700 mb-2'>Description</label>
                                            <textarea 
                                                name='description1' 
                                                id='editProductDescription'
                                                required 
                                                rows='3'
                                                class='w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
                                            ></textarea>
                                        </div>

                                        <!-- Price -->
                                        <div>
                                            <label class='block text-sm font-medium text-gray-700 mb-2'>Price</label>
                                            <input 
                                                type='number' 
                                                name='price1' 
                                                id='editProductPrice'
                                                required 
                                                min='0'
                                                step='0.01'
                                                class='w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
                                            >
                                        </div>

                                        <!-- Quantity -->
                                        <div>
                                            <label class='block text-sm font-medium text-gray-700 mb-2'>Quantity</label>
                                            <input 
                                                type='number' 
                                                name='quantity1' 
                                                id='editProductQuantity'
                                                required 
                                                min='0'
                                                class='w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
                                            >
                                        </div>

                                        <!-- Brand -->
                                        <div>
                                            <label class='block text-sm font-medium text-gray-700 mb-2'>Brand</label>
                                            <input 
                                                type='text' 
                                                name='brand1' 
                                                id='editProductBrand'
                                                required 
                                                class='w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
                                            >
                                        </div>

                                        <!-- Image Upload -->
                                        <div>
                                            <label class='block text-sm font-medium text-gray-700 mb-2'>Product Image</label>
                                            <input 
                                                type='file' 
                                                name='photo1' 
                                                id='editProductImage'
                                                class='w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500'
                                            >
                                        </div>
                                    </div>

                                    <!-- Modal Footer -->
                                    <div class='flex justify-end space-x-4 pt-6 border-t border-gray-200'>
                                        <button 
                                            type='button' 
                                            onclick='closeModal()' 
                                            class='px-4 py-2 text-gray-600 bg-gray-100 rounded-md hover:bg-gray-200 transition duration-300'
                                        >
                                            Cancel
                                        </button>
                                        <button 
                                            type='submit' 
                                            name='submitt' 
                                            class='px-4 py-2 bg-blue-600 text-white rounded-md hover:bg -blue-700 transition duration-300'
                                        >
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- JavaScript to handle modal open/close -->
                    <script>
                        function openModal(product) {
                            // Populate the modal with product data
                            document.getElementById('editPid').value = product.pid;
                            document.getElementById('editProductName').value = product.pname;
                            document.getElementById('editProductCategory').value = product.category;
                            document.getElementById('editProductDescription').value = product.description;
                            document.getElementById('editProductPrice').value = product.price;
                            document.getElementById('editProductQuantity').value = product.qtyavail;
                            document.getElementById('editProductBrand').value = product.brand;

                            // Show the modal
                            const modal = document.getElementById('editProductModal');
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                            
                            // Animate modal appearance
                            setTimeout(() => {
                                modal.querySelector('#modalContent').classList.remove('scale-95', 'opacity-0');
                                modal.querySelector('#modalContent').classList.add('scale-100', 'opacity-100');
                            }, 10);
                        }

                        function closeModal() {
                            const modal = document.getElementById('editProductModal');
                            modal.querySelector('#modalContent').classList.add('scale-95', 'opacity-0');
                            setTimeout(() => {
                                modal.classList.add('hidden');
                                modal.classList.remove('flex');
                            }, 300);
                        }
                    </script>";
    } else {
        echo "<script>alert('Product not found.'); window.location.href = 'inventory.php';</script>";
        exit();
    }
    $stmt->close();
}
    ?>

            <!-- Product Listing Section -->
            <div class="bg-white shadow-lg rounded-lg p-6 animate-fade-in">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Product List</h2>
                <div class="max-h-96 overflow-y-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Added</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            $query = "SELECT * FROM products";
                            $result = mysqli_query($con, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                $pid = $row['pid'];
                                echo '<tr>';
                                echo '<td class="px-6 py-4 whitespace-normal">' . htmlspecialchars($row['pname']) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-normal">' . htmlspecialchars($row['category']) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-normal">₱' . number_format($row['price'], 2) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-normal">' . htmlspecialchars($row['qtyavail']) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">' . date($row['date_created']) . '</td>';
                                echo "<td class='px-6 py-4 whitespace-nowrap'>
                                        <a href='inventory.php?pidd=" . $row['pid'] . "' onclick='openModal(" . json_encode($row) . ")' class='text-blue-600 mr-2 hover:text-blue-800'>Edit</a>
                                        <a href='#' onclick='openDeleteConfirmationModal(" . $row['pid'] . ")' class='text-red-600 hover:text-red-800'> Delete </a>
                                      </td>";
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Orders Section -->
            <div class="bg-white shadow-lg rounded-lg p-6 animate-fade-in">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Recent Orders</h2>
                <div class="max-h-96 overflow-y-auto custom-scrollbar">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 sticky top-0">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr ```php
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Fetch and display recent orders from the database
                            $orderQuery = "SELECT * FROM orders ORDER BY dateod DESC LIMIT 10";
                            $orderResult = mysqli_query($con, $orderQuery);
                            while ($orderRow = mysqli_fetch_assoc($orderResult)) {
                                echo '<tr>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($orderRow['oid']) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($orderRow['aid']) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($orderRow['dateod']) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">₱' . number_format($orderRow['total'], 2) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">' . htmlspecialchars($orderRow['datedel']) . '</td>';
                                echo '<td class="px-6 py-4 whitespace-nowrap">
                                        <a href="view_orders.php?id=' . $orderRow['oid'] . '" class="text-blue-600 hover:text-blue-800">View</a>
                                      </td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Confirmation Modal -->
<div 
    id="deleteConfirmationModal" 
    class="fixed inset-0 z-50 hidden items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none bg-black bg-opacity-50 transition-all duration-300"
>
    <div 
        class="relative w-full max-w-md mx-auto my-6 transform transition-all duration-300 ease-in-out scale-95 opacity-0"
        id="deleteModalContent"
    >
        <div class="bg-white rounded-lg shadow-2xl overflow-hidden animate-wiggle">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-5 border-b border-gray-200 bg-red-50">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-red-600 mr-3 animate-pulse" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-lg font-semibold text-red-800">Confirm Deletion</h3>
                </div>
                <button 
                    onclick="closeDeleteConfirmationModal()" 
                    class="text-gray-400 bg-transparent hover:bg-red-100 hover:text-red-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center transition-all duration-300"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 text-center">
                <div class="mb-4 flex justify-center">
                    <div class="animate-bounce-slow w-16 h-16 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 mb-5 text-lg">
                    Are you absolutely sure you want to delete this product?
                </p>
                <p class="text-sm text-gray-500 mb-5">
                    This action cannot be undone. All data associated with this product will be permanently removed.
                </p>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-center space-x-4 p-6 border-t border-gray-200 bg-gray-50">
                <button 
                    type="button" 
                    onclick="closeDeleteConfirmationModal()" 
                    class="px-6 py-2 text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-100 transition duration-300 transform hover:scale-105"
                >
                    Cancel
                </button>
                <button 
                    id="confirmDeleteButton" 
                    class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300 transform hover:scale-105 active:scale-95 flex items-center"
                >
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    Delete Permanently
                </button>
            </div>
        </div>
    </div>
</div>


    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 MyTechPC. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>

<script>
let productIdToDelete = null;

function openDeleteConfirmationModal(productId) {
    productIdToDelete = productId; // Store the product ID to delete
    const modal = document.getElementById('deleteConfirmationModal');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modal.classList.add('flex');
        modal.querySelector('#deleteModalContent').classList.remove('scale-95', 'opacity-0');
        modal.querySelector('#deleteModalContent').classList.add('scale-100', 'opacity-100');
    }, 10);
}

// Confirm deletion
document.getElementById('confirmDeleteButton').addEventListener('click', function() {
    if (productIdToDelete) {
        // Create a form dynamically to submit the delete request
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = 'inventory.php'; // Your existing page handling deletion

        const pidInput = document.createElement('input');
        pidInput.type = 'hidden';
        pidInput.name = 'pid';
        pidInput.value = productIdToDelete;

        form.appendChild(pidInput);
        document.body.appendChild(form);
        form.submit();
    }
});

function closeDeleteConfirmationModal() {
    const modal = document.getElementById('deleteConfirmationModal');
    modal.querySelector('#deleteModalContent').classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
};

window.addEventListener("unload", function() {
  // Call a PHP script to log out the user
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "logout.php", false);
  xhr.send();
});
</script>