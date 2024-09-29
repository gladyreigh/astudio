import React, { useState } from 'react';
import { useData } from '../contexts/DataContext';

const Filters = ({ fields, endpoint }) => {
  const { pageSize, setPageSize, fetchData } = useData();
  const [searchVisible, setSearchVisible] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');
  const [filters, setFilters] = useState({});

  const handlePageSizeChange = (e) => {
    const newSize = parseInt(e.target.value);
    setPageSize(newSize);
    fetchData(endpoint, filters);
  };

  const handleSearchToggle = () => {
    setSearchVisible(!searchVisible);
  };

  const handleSearchChange = (e) => {
    setSearchTerm(e.target.value);
  };

  const handleFilterChange = (field, value) => {
    const newFilters = { ...filters, [field]: value };
    setFilters(newFilters);
    fetchData(endpoint, newFilters);
  };

  return (
    <div style={{ marginBottom: '20px' }}>
      <select value={pageSize} onChange={handlePageSizeChange}>
        {[5, 10, 20, 50].map((size) => (
          <option key={size} value={size}>
            {size}
          </option>
        ))}
      </select>
      <button onClick={handleSearchToggle}>🔍</button>
      {searchVisible && (
        <input
          type="text"
          value={searchTerm}
          onChange={handleSearchChange}
          placeholder="Search..."
        />
      )}
      {fields.map((field) => (
        <input
          key={field}
          type="text"
          placeholder={`Filter by ${field}`}
          onChange={(e) => handleFilterChange(field, e.target.value)}
        />
      ))}
    </div>
  );
};

export default Filters;