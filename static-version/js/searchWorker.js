// static-version/js/searchWorker.js
self.onmessage = function (e) {
    const { products, searchQuery } = e.data;
    const filteredProducts = searchQuery
        ? products.filter(product => product.product_name.toLowerCase().includes(searchQuery.toLowerCase()))
        : products;
    self.postMessage(filteredProducts);
};