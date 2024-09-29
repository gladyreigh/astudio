// App.js
import React, { useState } from 'react';
import { BrowserRouter as Router, Route, Routes, Link } from 'react-router-dom';
import { DataProvider } from './contexts/DataContext';
import Users from './pages/Users';
import Products from './pages/Products';
import { FaUsers, FaBoxes, FaBars, FaTimes } from 'react-icons/fa';
import './App.css';

function App() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <DataProvider>
      <Router>
        <div className="app">
          <nav className="navbar">
            <div className="navbar-brand">ASTUDIO</div>
            <button className="menu-toggle" onClick={() => setIsMenuOpen(!isMenuOpen)}>
              {isMenuOpen ? <FaTimes /> : <FaBars />}
            </button>
            <ul className={`navbar-menu ${isMenuOpen ? 'open' : ''}`}>
              <li>
                <Link to="/users" onClick={() => setIsMenuOpen(false)}><FaUsers /> Users</Link>
              </li>
              <li>
                <Link to="/products" onClick={() => setIsMenuOpen(false)}><FaBoxes /> Products</Link>
              </li>
            </ul>
          </nav>

          <main className="main-content">
            <Routes>
              <Route path="/users" element={<Users />} />
              <Route path="/products" element={<Products />} />
            </Routes>
          </main>

          <footer className="footer">
            <p>© 2024 ASTUDIO. All rights reserved.</p>
          </footer>
        </div>
      </Router>
    </DataProvider>
  );
}

export default App;