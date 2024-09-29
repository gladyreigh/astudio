import React from 'react';
import { useData } from '../contexts/DataContext';
import '../Pagination.css'; // We'll create this file for styles

const Pagination = ({ endpoint }) => {
  const { currentPage, setCurrentPage, totalPages, fetchData } = useData();

  const handlePageChange = (newPage) => {
    if (newPage >= 1 && newPage <= totalPages) {
      setCurrentPage(newPage);
      fetchData(endpoint);
    }
  };

  const renderPageNumbers = () => {
    const pageNumbers = [];
    const showPages = 5; // Number of page buttons to show

    let startPage = Math.max(1, currentPage - Math.floor(showPages / 2));
    let endPage = Math.min(totalPages, startPage + showPages - 1);

    if (endPage - startPage + 1 < showPages) {
      startPage = Math.max(1, endPage - showPages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
      pageNumbers.push(
        <button
          key={i}
          onClick={() => handlePageChange(i)}
          className={i === currentPage ? 'active' : ''}
        >
          {i}
        </button>
      );
    }

    return pageNumbers;
  };

  return (
    <div className="pagination">
      <button
        onClick={() => handlePageChange(currentPage - 1)}
        disabled={currentPage === 1}
        className="nav-button"
      >
        Previous
      </button>

      {currentPage > 3 && totalPages > 5 && (
        <>
          <button onClick={() => handlePageChange(1)}>1</button>
          {currentPage > 4 && <span className="ellipsis">...</span>}
        </>
      )}

      {renderPageNumbers()}

      {currentPage < totalPages - 2 && totalPages > 5 && (
        <>
          {currentPage < totalPages - 3 && <span className="ellipsis">...</span>}
          <button onClick={() => handlePageChange(totalPages)}>{totalPages}</button>
        </>
      )}

      <button
        onClick={() => handlePageChange(currentPage + 1)}
        disabled={currentPage === totalPages}
        className="nav-button"
      >
        Next
      </button>
    </div>
  );
};

export default Pagination;