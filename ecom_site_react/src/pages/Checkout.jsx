import React, {useContext,useEffect,useState} from "react";
import {Link } from "react-router-dom";
import { useSsrState, useSsrEffect } from '@issr/core';
import {BaseUrlContext, AuthContext, CartContext} from '../App';

import toast, { Toaster } from 'react-hot-toast';

const Checkout = ()=>{

   
    const base_url = useContext(BaseUrlContext);
    const [cart, setCart] = useContext(CartContext);
    const [LoggedIn, setLoggedIn] = useContext(AuthContext);
    const [TotalCart, TotalCartData] = useState([]);
    const [Subtotal, setSubtotal] = useState(0); 

 
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


    return(
        <>
            <main className="main">
                
                <div className="container mb-80 mt-50">
                    <div className="row">
                        <div className="col-lg-8 mb-40">
                            <h1 className="heading-2 mb-10">Checkout</h1>
                            
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-lg-7">
                           
                            <div className="row">
                                <h4 className="mb-30">Billing Details</h4>
                                <form method="post">
                                    <div className="row">
                                        <div className="form-group col-lg-6">
                                            <input type="text" required="" name="fname" placeholder="First name *" />
                                        </div>
                                        <div className="form-group col-lg-6">
                                            <input type="text" required="" name="lname" placeholder="Last name *" />
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="form-group col-lg-6">
                                            <input type="text" name="billing_address" required="" placeholder="Address *" />
                                        </div>
                                        <div className="form-group col-lg-6">
                                            <input type="text" name="billing_address2" required="" placeholder="Address line2" />
                                        </div>
                                    </div>
                                    <div className="row shipping_calculator">
                                        <div className="form-group col-lg-6">
                                            <div className="custom_select">
                                                <select className="form-control select-active">
                                                    <option value="">Select an option...</option>
                                                    <option value="AX">Aland Islands</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div className="form-group col-lg-6">
                                            <input required="" type="text" name="city" placeholder="City / Town *" />
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="form-group col-lg-6">
                                            <input required="" type="text" name="zipcode" placeholder="Postcode / ZIP *" />
                                        </div>
                                        <div className="form-group col-lg-6">
                                            <input required="" type="text" name="phone" placeholder="Phone *" />
                                        </div>
                                    </div>
                                    <div className="row">
                                        <div className="form-group col-lg-6">
                                            <input required="" type="text" name="cname" placeholder="Company Name" />
                                        </div>
                                        <div className="form-group col-lg-6">
                                            <input required="" type="text" name="email" placeholder="Email address *" />
                                        </div>
                                    </div>
                                    <div className="form-group mb-30">
                                        <textarea rows="5" placeholder="Additional information"></textarea>
                                    </div>
                                    <div className="form-group">
                                        <div className="checkbox">
                                            <div className="custome-checkbox">
                                                <input className="form-check-input" type="checkbox" name="checkbox" id="createaccount" />
                                                <label className="form-check-label label_info" data-bs-toggle="collapse" href="#collapsePassword" data-target="#collapsePassword" aria-controls="collapsePassword" for="createaccount"><span>Create an account?</span></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="collapsePassword" className="form-group create-account collapse in">
                                        <div className="row">
                                            <div className="col-lg-6">
                                                <input required="" type="password" placeholder="Password" name="password" />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="ship_detail">
                                        <div className="form-group">
                                            <div className="chek-form">
                                                <div className="custome-checkbox">
                                                    <input className="form-check-input" type="checkbox" name="checkbox" id="differentaddress" />
                                                    <label className="form-check-label label_info" data-bs-toggle="collapse" data-target="#collapseAddress" href="#collapseAddress" aria-controls="collapseAddress" for="differentaddress"><span>Ship to a different address?</span></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="collapseAddress" className="different_address collapse in">
                                            <div className="row">
                                                <div className="form-group col-lg-6">
                                                    <input type="text" required="" name="fname" placeholder="First name *" />
                                                </div>
                                                <div className="form-group col-lg-6">
                                                    <input type="text" required="" name="lname" placeholder="Last name *" />
                                                </div>
                                            </div>
                                            <div className="row shipping_calculator">
                                                <div className="form-group col-lg-6">
                                                    <input required="" type="text" name="cname" placeholder="Company Name" />
                                                </div>
                                                <div className="form-group col-lg-6">
                                                    <div className="custom_select w-100">
                                                        <select className="form-control select-active">
                                                            <option value="">Select an option...</option>
                                                            <option value="AX">Aland Islands</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="form-group col-lg-6">
                                                    <input type="text" name="billing_address" required="" placeholder="Address *" />
                                                </div>
                                                <div className="form-group col-lg-6">
                                                    <input type="text" name="billing_address2" required="" placeholder="Address line2" />
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="form-group col-lg-6">
                                                    <input required="" type="text" name="state" placeholder="State / County *" />
                                                </div>
                                                <div className="form-group col-lg-6">
                                                    <input required="" type="text" name="city" placeholder="City / Town *" />
                                                </div>
                                            </div>
                                            <div className="row">
                                                <div className="form-group col-lg-6">
                                                    <input required="" type="text" name="zipcode" placeholder="Postcode / ZIP *" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div className="col-lg-5">
                            <div className="border p-40 cart-totals ml-30 mb-50">
                                <div className="d-flex align-items-end justify-content-between mb-30">
                                    <h4>Your Order</h4>
                                    <h6 className="text-muted">Subtotal</h6>
                                </div>
                                <div className="divider-2 mb-30"></div>
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
                                              
                                                <td scope="col" colspan="2">
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
                            </div>
                            <div className="payment ml-30">
                                <h4 className="mb-30">Payment</h4>
                                <div className="payment_option">
                                    
                                    <div className="custome-radio">
                                        <input className="form-check-input" required="" type="radio" name="payment_option" id="exampleRadios4" checked="" />
                                        <label className="form-check-label" for="exampleRadios4" data-bs-toggle="collapse" data-target="#checkPayment" aria-controls="checkPayment">Cash on delivery</label>
                                    </div>
                                    <div className="custome-radio">
                                        <input className="form-check-input" required="" type="radio" name="payment_option" id="exampleRadios5" checked="" />
                                        <label className="form-check-label" for="exampleRadios5" data-bs-toggle="collapse" data-target="#paypal" aria-controls="paypal">Online Getway</label>
                                    </div>
                                </div>
                                <a href="#" className="btn btn-fill-out btn-block mt-30">Place an Order<i className="fi-rs-sign-out ml-15"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            
        </>
    )
}
export default Checkout;