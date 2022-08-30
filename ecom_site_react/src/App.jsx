import React,{createContext, useState, useEffect} from 'react';
import { Switch, Route } from 'react-router-dom';
import Home from './Home';
import About from './pages/About';
import Contact from './pages/Contact';
import Products from './pages/Products';
import Blogs from './pages/Blogs';
import Blog from './pages/Blog';
import Product from './pages/Product';
import PageNotFound from './pages/PageNotFound';
import Header from './Header';
import Footer from './Footer';
import Login from './pages/Login';
import Register from './pages/Register';
import CartPage from './pages/CartPage';
import Checkout from './pages/Checkout';
import Whishlist from './pages/Whishlist';

  
const BaseUrlContext = createContext('');
const CartContext = createContext('');
const AuthContext = createContext('');

const App = () => {

  
  const basrurl = 'http://localhost/react/prince_ecomm_react/prince_app/api';

  const [cart, setCart] = useState();
  const [LoggedIn, setLoggedIn] = useState();

  
  useEffect(() => {
    
      setLoggedIn(localStorage.getItem('Token'));
      
  }, [LoggedIn])

  useEffect( () => {

    if(LoggedIn){
      
      setCart(cart);

    }else{

      setCart(localStorage.getItem('cart_item'));

    } 
  }, [cart])


    return (
      <>
          <BaseUrlContext.Provider value={basrurl}>
            
              <CartContext.Provider value={[cart, setCart]}>
                  <AuthContext.Provider value={[LoggedIn, setLoggedIn]}>
                      <Header />
                      <Switch>
                        
                        <Route path="/" component={Home} exact />
                        <Route path="/login" component={Login} exact />
                        <Route path="/register" component={Register} exact />
                        <Route path="/about" component={About} exact />
                        <Route path="/contact" component={Contact} exact />
                        <Route path="/products" component={Products} exact />
                        <Route path="/products/:category" component={Products} exact />
                        <Route path="/blogs" component={Blogs} exact />
                        <Route path="/blog/:slug" component={Blog} exact />
                        <Route path="/product/:slug" component={Product} exact />
                        <Route path="/cart" component={CartPage} exact />
                        <Route path="/wishlist" component={Whishlist} exact />
                        <Route path="/checkout" component={Checkout} exact />
                        <Route path="*" component={PageNotFound} exact />
                      </Switch>
                      <Footer />
                  
                  </AuthContext.Provider>
              </CartContext.Provider>
          </BaseUrlContext.Provider>
      </>
    )

}

export { BaseUrlContext, CartContext, AuthContext }

export default App;
