import React from 'react';
import { useData } from '../contexts/DataContext';

const Pagination = ({ endpoint }) => {
  const { currentPage, setCurrentPage, totalPages, fetchData } = useData();

  const handlePageChange = (newPage) => {
    setCurrentPage(newPage);
    fetchData(endpoint);
  };

  return (
    <div>
      {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
        <button
          key={page}
          onClick={() => handlePageChange(page)}
          disabled={page === currentPage}
          style={{ margin: '0 5px' }}
        >
          {page}
        </button>
      ))}
    </div>
  );
};

export default Pagination;