import React, { useEffect, useState } from 'react';
import { useData } from '../contexts/DataContext';
import DataTable from '../components/DataTable';
import Filters from '../components/Filters';
import Pagination from '../components/Pagination';

const Products = () => {
  const { fetchData } = useData();
  const [activeTab, setActiveTab] = useState('ALL');

  useEffect(() => {
    fetchData('products', activeTab === 'Laptops' ? { category: 'laptops' } : {});
  }, [activeTab]);

  const columns = ['id', 'title', 'description', 'price', 'discountPercentage', 'rating', 'stock', 'brand', 'category', 'thumbnail'];
  const filterFields = ['title', 'brand', 'category'];

  return (
    <div>
      <h1>Products</h1>
      <div>
        <button onClick={() => setActiveTab('ALL')} disabled={activeTab === 'ALL'}>ALL</button>
        <button onClick={() => setActiveTab('Laptops')} disabled={activeTab === 'Laptops'}>Laptops</button>
      </div>
      <Filters fields={filterFields} endpoint="products" />
      <DataTable columns={columns} />
      <Pagination endpoint="products" />
    </div>
  );
};

export default Products;