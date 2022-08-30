import React,{useContext,useEffect,useState} from "react";
import {Link } from "react-router-dom";
import { useSsrState, useSsrEffect } from '@issr/core';
import {BaseUrlContext, AuthContext, CartContext} from '../App';

import AddToCart from './cart/AddToCart';
import toast, { Toaster } from 'react-hot-toast';

const CartPage = ()=>{

   
    const [cartData, setCartData] = useState([]);
    const base_url = useContext(BaseUrlContext);
    const [cart, setCart] = useContext(CartContext);
    const [LoggedIn, setLoggedIn] = useContext(AuthContext);
    const [TotalCart, TotalCartData] = useState([]);
    const [Subtotal, setSubtotal] = useState(0);
    const [qtyIncre, setQty] = useState(0);

    
    
    useEffect(() => {
        if(LoggedIn){

            TotalCartData(cart ?? []);
           
        }else{

            if(localStorage.getItem('cart_item')){
                TotalCartData(JSON.parse(localStorage.getItem('cart_item')));
                //console.log(TotalCart)
                let _total = 0;
                TotalCart.forEach((item) => {
                _total += ((item.quantity)*(item.price));
                });
                setSubtotal((_total).toFixed(2)); 
            }
        }
        
    }, [cart])

    
    const removeToCart = (product_id) =>{

        if(product_id) { 

            if(LoggedIn){

                setCartIsLoaded(true);

                fetch(value+'delete-cart',
                {
                    method: "POST",
                    headers: {
                            Accept: "application/json",
                            "Content-Type": "application/json",
                            Authorization: `Bearer ${LoggedIn}`,
                    },
                    body: JSON.stringify({
                        id: product_id,
                    }),
                }).then(res => res.json())
                .then(
                    (result) => {
                        setCartIsLoaded(false);
                        if(result.error){
                            toast.error(result.message, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                        }else{
                            
                            toast.success(result.message, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                            fetch(value+'cart-list', {
                                method: "GET",
                                headers: {
                                        Accept: "application/json",
                                        "Content-Type": "application/json",
                                        Authorization: `Bearer ${LoggedIn}`,
                                },
                            }).then(res => res.json())
                            .then(
                                (result) => {
                                    setCartIsLoaded(false);
                                    if(!result.error){
                                        if(result.result){
                                            
                                            setCart(result.result);
                                        }
                                        
                                    }else{
                                        setCart(result.result);
                                        TotalCartData(result.result);
                                        setSubtotal((0).toFixed(2)); 
                                    }
                                
                                }
                            )
                        }
                    
                    },
                    (error) => {
                        toast.error(error, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                        setCartIsLoaded(false);
                        
                    }
                )

            }else{

                for (var i = TotalCart.length; i--;) {
                    if (TotalCart[i].productid === product_id) TotalCart.splice(i, 1);
                }
    
                setCart(localStorage.setItem("cart_item", JSON.stringify(TotalCart)));
                toast.success('Item removed from cart successfully.',{position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
            }
            
        }

    }

    
     
    const addToCart2 = (product_id,name,price,img) =>{
        
        setCartData({product_id,name,price,img});
         
    }

    const QtyIncrement = (product_id,name,price,img,qty) =>{
        
        const quantity = qty+1;
        setQty(quantity);
        
        setCartData({product_id,name,price,img,quantity});
         
    }

    
    const QtyDecrement = (product_id,name,price,img,qty) =>{
        
        const quantity = qty-1;
        if(quantity > 0){
            
            setQty(quantity);
            setCartData({product_id,name,price,img,quantity});
        }else{
            for (var i = TotalCart.length; i--;) {
                if (TotalCart[i].productid === product_id) TotalCart.splice(i, 1);
            }

            setCart(localStorage.setItem("cart_item", JSON.stringify(TotalCart)));
        }
        
         
    }

    return(
        <>
            {cartData.length != 0 ? <AddToCart data={cartData}/> :<></>}
            <main className="main">
                
                <div className="container mb-80 mt-50">
                    <div className="row">
                        <div className="col-lg-8 mb-40">
                            <h1 className="heading-2 mb-10">Your Cart</h1>
                            <div className="d-flex justify-content-between">
                                <h6 className="text-body">There are <span className="text-brand">{TotalCart.length ?? 0}</span> products in your cart</h6>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-lg-8">
                            <div className="table-responsive shopping-summery">
                                <table className="table table-wishlist">
                                    <thead>
                                        <tr className="main-heading">
                                            
                                            <th scope="col" colSpan="2" className="text-center">Product</th>
                                            <th scope="col">Unit Price</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Subtotal</th>
                                            <th scope="col" >Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        { TotalCart.length != 0 ? TotalCart.map((item, index) => (
                                            <tr className="pt-30">
                                                
                                                <td className="image product-thumbnail pt-20 pl-10"><img src={item.img} alt="#" /></td>
                                                <td className="product-des product-name">
                                                    <h6 className="mb-5"><a className="product-name mb-10 text-heading" href="">{item.name}</a></h6>
                                                    
                                                </td>
                                                <td className="price" data-title="Price">
                                                    <h4 className="text-body">Rs. {item.price} </h4>
                                                </td>
                                                <td className="text-center detail-info" data-title="Stock">
                                                    <div className="detail-extralink mr-15">
                                                        <div className="detail-qty border radius">
                                                            <a href="#" onClick={(e) => { e.preventDefault(); QtyDecrement(item.productid,item.name,item.price,item.img,item.quantity)}} style={{right:"70%"}}><i className="fa fa-minus"></i></a>
                                                            <span className="qty-val">{item.quantity ?? qtyIncre}</span>
                                                            <a href="#" onClick={(e) => { e.preventDefault(); QtyIncrement(item.productid,item.name,item.price,item.img,item.quantity)}} ><i className="fa fa-plus"></i></a>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="price" data-title="Price">
                                                    <h4 className="text-brand">Rs. {(item.quantity)*(item.price)}</h4>
                                                </td>
                                                <td className="action text-center" data-title="Remove"><a href="#" className="text-body" onClick={(e) => { e.preventDefault(); removeToCart(item.productid)}}><i className="fa fa-trash"></i></a></td>
                                            </tr>
                                            )) : 
                                            <tr>
                                                <td colSpan="5" >
                                                    <div className="thankyou-text text-center p-10">
                                                        <h4><span>Oops!</span> Your cart is empty!</h4>
                                                        <p>Looks like you haven't added anything to your cart yet.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            

                                        }
                                    </tbody>
                                </table>
                            </div>
                            <div className="divider-2 mb-30"></div>
                            <div className="cart-action d-flex justify-content-between">
                                <Link className="btn " to="/products"><i className="fa fa-arrow-left mr-10"></i>Continue Shopping</Link>
                            </div>
                            
                        </div>
                        <div className="col-lg-4">
                            <div className="row">
                                
                                <div className="col-lg-12 " >
                                    <div className="p-40 border p-md-4 cart-totals ml-30">
                                        <h4 className="mb-10">Apply Coupon</h4>
                                        <p className="mb-30"><span className="font-lg text-muted">Using A Promo Code?</span></p>
                                        <form action="#">
                                            <div className="d-flex justify-content-between">
                                                <input className="font-medium mr-15 coupon" name="Coupon" placeholder="Enter Your Coupon" />
                                                <button className="btn">Apply</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div><br></br>
                            <div className="border p-md-4 cart-totals ml-30">
                                <div className="table-responsive">
                                    <table className="table no-border">
                                        <tbody>
                                            <tr>
                                                <td className="cart_total_label">
                                                    <h6 className="text-muted">Subtotal</h6>
                                                </td>
                                                <td className="cart_total_amount">
                                                    <h4 className="text-brand text-end">Rs. {Subtotal}</h4>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <td className="cart_total_label">
                                                    <h6 className="text-muted">Shipping</h6>
                                                </td>
                                                <td className="cart_total_amount">
                                                    <h5 className="text-heading text-end">Free</h5></td> 
                                              
                                                <td scope="col" colSpan="2">
                                                    <div className="divider-2 mt-10 mb-10"></div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td className="cart_total_label">
                                                    <h6 className="text-muted">Total</h6>
                                                </td>
                                                <td className="cart_total_amount">
                                                    <h4 className="text-brand text-end">Rs. {Subtotal}</h4>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <Link to="/checkout" className="btn mb-20 w-100">Proceed To CheckOut<i className="fa fa-sign-out ml-15"></i></Link>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            
        </>
    )
}
export default CartPage;