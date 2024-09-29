import React from 'react';
import { BrowserRouter as Router, Route, Routes, Link } from 'react-router-dom';
import { DataProvider } from './contexts/DataContext';
import Users from './pages/Users';
import Products from './pages/Products';

function App() {
  return (
    <DataProvider>
      <Router>
        <div style={{ fontFamily: 'Neutra Text, sans-serif', color: '#322625' }}>
          <nav>
            <ul>
              <li>
                <Link to="/users">Users</Link>
              </li>
              <li>
                <Link to="/products">Products</Link>
              </li>
            </ul>
          </nav>

          <Routes>
            <Route path="/users" element={<Users />} />
            <Route path="/products" element={<Products />} />
          </Routes>
        </div>
      </Router>
    </DataProvider>
  );
}

export default App;