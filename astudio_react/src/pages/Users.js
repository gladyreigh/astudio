import React, { useEffect } from 'react';
import { useData } from '../contexts/DataContext';
import DataTable from '../components/DataTable';
import Filters from '../components/Filters';
import Pagination from '../components/Pagination';

const Users = () => {
  const { fetchData } = useData();

  useEffect(() => {
    fetchData('users');
  }, []);

  const columns = ['id', 'firstName', 'lastName', 'age', 'gender', 'email', 'phone', 'username', 'birthDate', 'bloodGroup', 'height', 'weight'];
  const filterFields = ['firstName', 'lastName', 'age', 'gender'];

  return (
    <div>
      <h1>Users</h1>
      <Filters fields={filterFields} endpoint="users" />
      <DataTable columns={columns} />
      <Pagination endpoint="users" />
    </div>
  );
};

export default Users;