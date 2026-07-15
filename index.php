<?php
include 'config.php';
include 'header.php';

$cat_query = mysqli_query($conn, "SELECT DISTINCT category FROM products");
$category_filter = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : '';$where_clause = $category_filter ? "WHERE category = '$category_filter'" : '';

$products_query = mysqli_query($conn, "SELECT * FROM products $where_clause");
?>

<div class="bg-white border border-gray-100 rounded-xl shadow-sm p-8 mb-8 text-center">
    <span class="text-indigo-600 font-bold tracking-widest text-xs uppercase">New Season Arrivals</span>
    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 tracking-tight mt-2">Featured Apparel Catalog</h1>
    <p class="text-gray-400 text-sm mt-2 max-w-lg mx-auto">Collegiate outerwear, custom button-downs, and minimalist lifestyle apparel.</p>
</div>

<div class="flex flex-col md:flex-row gap-8">
    
    <div class="w-full md:w-1/4">
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-4 md:sticky md:top-20">
            <h3 class="font-bold text-sm tracking-wider uppercase text-gray-400 mb-3 px-2">Shop By Category</h3>
            <ul class="space-y-1">
                <li>
                    <a href="index.php" class="block px-3 py-2 text-xs font-bold tracking-wider uppercase rounded-lg transition <?= !$category_filter ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:bg-gray-50' ?>">
                        All Collections
                    </a>
                </li>
                <?php while($cat = mysqli_fetch_assoc($cat_query)): ?>
                    <li>
                        <a href="index.php?category=<?= urlencode($cat['category']) ?>" class="block px-3 py-2 text-xs font-bold tracking-wider uppercase rounded-lg transition <?= $category_filter ===$cat['category'] ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600 hover:bg-gray-50' ?>">
                            <?= htmlspecialchars($cat['category']) ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <div class="w-full md:w-3/4">
        <?php if (mysqli_num_rows($products_query) == 0): ?>
            <div class="text-center py-16 bg-white border border-gray-100 rounded-xl shadow-sm">
                <svg class="mx-auto mb-4 text-gray-300" width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="4" y="7" width="16" height="13" rx="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 7V5a3 3 0 0 1 6 0v2" stroke-linecap="round"/>
                </svg>
                <h3 class="text-lg font-bold text-gray-700">No products found in this category</h3>
                <a href="index.php" class="inline-block mt-4 text-xs text-indigo-600 font-bold hover:underline">View All Collections</a>
            </div>
        <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($product = mysqli_fetch_assoc($products_query)): ?>
                <div class="bg-white border border-gray-100 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition p-4 flex flex-col justify-between">
                    <div>
                        <div class="h-64 bg-gray-50 border border-gray-100 rounded-lg overflow-hidden flex items-center justify-center p-2 relative">
                            <?php if ($product['stock'] <= 5 && $product['stock'] > 0): ?>
                                <span class="absolute top-2 left-2 text-xxs font-bold uppercase tracking-wider bg-red-50 text-red-600 px-2 py-0.5 rounded-full">Low Stock</span>
                            <?php elseif ($product['stock'] == 0): ?>
                                <span class="absolute top-2 left-2 text-xxs font-bold uppercase tracking-wider bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Sold Out</span>
                            <?php endif; ?>
                            <?php if (!empty($product['image']) && file_exists($product['image'])): ?>
                                <img src="<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="h-full w-full object-contain mix-blend-multiply">
                            <?php else: ?>
                                <div class="text-center p-4">
                                    <div class="text-gray-400 font-bold text-xs uppercase tracking-wider mb-1">No Image File Located</div>
                                    <span class="text-gray-300 text-xxs font-mono block break-all"><?= htmlspecialchars($product['image']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="mt-4">
                            <span class="text-xxs font-bold uppercase tracking-widest text-indigo-600"><?= htmlspecialchars($product['category']) ?></span>
                            <h4 class="font-bold text-gray-800 text-sm mt-1 h-10 overflow-hidden line-clamp-2"><?= htmlspecialchars($product['name']) ?></h4>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-indigo-600 font-black text-base">PHP <?= number_format($product['price'], 2) ?></span>
                            <span class="text-xxs text-gray-400 uppercase font-bold tracking-wider">Stock: <?= htmlspecialchars($product['stock']) ?></span>
                        </div>
                        
                        <form action="cart.php" method="POST" class="mt-4">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <?php if ($product['stock'] > 0): ?>
                                <button type="submit" name="add_to_cart" class="w-full bg-indigo-600 text-white text-xs py-2.5 rounded hover:bg-indigo-700 transition font-bold uppercase tracking-wider">
                                    Add To Cart
                                </button>
                            <?php else: ?>
                                <button type="button" disabled class="w-full bg-gray-100 text-gray-400 text-xs py-2.5 rounded font-bold uppercase tracking-wider cursor-not-allowed">
                                    Out Of Stock
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>