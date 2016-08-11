/*	a. Для заданного списка товаров получить названия всех категорий, в которых представлены товары; */
	SELECT DISTINCT name FROM category
	LEFT JOIN product_category ON product_category.category = category.id 
	WHERE product_category.product IN('1','3','6');

/*	b. Для заданной категории получить список предложений всех товаров из этой категории и ее дочерних категорий; */
	SELECT products.id, products.name FROM products 
	LEFT JOIN product_category ON product_category.product = products.id 
	WHERE product_category.category = '20'
	UNION
	SELECT products.id, products.name FROM products
	LEFT JOIN product_category ON product_category.product = products.id
	WHERE product_category.category IN(SELECT id FROM category WHERE parent = '1')

/*	c. Для заданного списка категорий получить количество предложений товаров в каждой категории; */
	SELECT category, COUNT(product) FROM product_category
	WHERE category IN('1','3','23') GROUP BY category

/*	d. Для заданного списка категорий получить общее количество уникальных предложений товара; */
	SELECT SUM(count) AS sum FROM (
		SELECT product_category.category, COUNT(product_category.product) AS count FROM product_category 
		WHERE product_category.category IN('1','4') 
		AND product_category.product NOT IN (
			SELECT help_product_category.product FROM product_category AS help_product_category
			WHERE help_product_category.category IN('1','4') AND help_product_category.category != product_category.category
		)
		GROUP BY category
	) AS T

/*	e. Для заданной категории получить ее полный путь в дереве (breadcrumb, «хлебные крошки»). */
	USE test_task;
	SELECT t1.name AS breadcr1, t2.name as breadcr2, t3.name as breadcr3, t4.name as breadcr4 FROM category AS t1
	LEFT JOIN category AS t2 ON t2.parent = t1.id
	LEFT JOIN category AS t3 ON t3.parent = t2.id
	LEFT JOIN category AS t4 ON t4.parent = t3.id
	WHERE t4.name = 'Рюкзаки';
