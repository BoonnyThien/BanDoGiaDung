// static-version/js/mockApi.js
const { rest } = window.msw;
const { setupWorker } = window.msw;

fetch('./web_giadung.json')
    .then(response => response.json())
    .then(jsonData => {
        const products = jsonData.tables.find(table => table.name === "tbl_product").data;
        const brands = jsonData.tables.find(table => table.name === "tbl_brand").data;
        const categories = jsonData.tables.find(table => table.name === "tbl_category").data;

        const handlers = [
            rest.post('/graphql', (req, res, ctx) => {
                const { query } = req.body;

                if (query.includes('products')) {
                    return res(
                        ctx.json({
                            data: {
                                products
                            }
                        })
                    );
                }

                if (query.includes('product')) {
                    const id = query.match(/id: "(\d+)"/)?.[1];
                    const product = products.find(p => p.product_id === id);
                    return res(
                        ctx.json({
                            data: {
                                product
                            }
                        })
                    );
                }

                if (query.includes('brands')) {
                    return res(
                        ctx.json({
                            data: {
                                brands
                            }
                        })
                    );
                }

                if (query.includes('categories')) {
                    return res(
                        ctx.json({
                            data: {
                                categories
                            }
                        })
                    );
                }

                return res(ctx.status(400));
            })
        ];

        const worker = setupWorker(...handlers);
        worker.start();
    });