import React, { createContext, useState, useContext } from 'react';
import axios from 'axios';

const DataContext = createContext();

export const DataProvider = ({ children }) => {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [pageSize, setPageSize] = useState(5);
  const [currentPage, setCurrentPage] = useState(1);
  const [totalPages, setTotalPages] = useState(1);

  const fetchData = async (endpoint, filters = {}) => {
    setLoading(true);
    try {
      const response = await axios.get(`https://dummyjson.com/${endpoint}`, {
        params: {
          limit: pageSize,
          skip: (currentPage - 1) * pageSize,
          ...filters,
        },
      });
      setData(response.data[endpoint]);
      setTotalPages(Math.ceil(response.data.total / pageSize));
      setError(null);
    } catch (err) {
      setError('Error fetching data');
    }
    setLoading(false);
  };

  return (
    <DataContext.Provider
      value={{
        data,
        loading,
        error,
        pageSize,
        setPageSize,
        currentPage,
        setCurrentPage,
        totalPages,
        fetchData,
      }}
    >
      {children}
    </DataContext.Provider>
  );
};

export const useData = () => useContext(DataContext);