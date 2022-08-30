import React from 'react';
import { useState, useEffect } from "react";   
import { useContext } from "react";
import {BaseUrlContext, CartContext, AuthContext} from '../../App';

import toast, { Toaster } from 'react-hot-toast';

const AddToCart = ({data}) => {
    
    const base_url = useContext(BaseUrlContext);
    const [cart, setCart] = useContext(CartContext);
    const [LoggedIn, setLoggedIn] = useContext(AuthContext);
    const [Isloading, setIsLoaded] = useState(false);
    const [CartIsloading, setCartIsLoaded] = useState(false);


    useEffect( () => {

        if(!data.quantity){
            var quantity = 1;
        }else{
            
            var quantity = data.quantity;
           
        }
        
        console.log(data.product_id);
        if(LoggedIn){

            setCartIsLoaded(true);
            fetch(base_url+"add-cart",
            {
                method: "POST",
                headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                        Authorization: `Bearer ${LoggedIn}`,
                },
                body: JSON.stringify({
                        add_type: "add",
                        product_id: data.product_id,
                        qty: quantity,
                }),
            }
            ).then((res) => res.json())
            .then((result)=>{
                if(!result.error){
                    toast.error(result.message, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                    setCartIsLoaded(false);         
                    fetch(base_url+'cart-list',
                    {
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
                            if(result.error){
                                toast.error(result.message, {position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                            }else{
                                setCart(result.result);
                            }
                        
                        },
                        (error) => {
                            setCartIsLoaded(false);
                        }
                    )

                }
            });
            
         }else{

            if(cart == null) { 
                
                var products = [];
                var product = {productid : data.product_id ,quantity : quantity,name : data.name,price : data.price,img : data.img};
                products.push(product);
                setCart(localStorage.setItem("cart_item", JSON.stringify(products)));
                toast.success('Product is added to cart',{position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                
            }else {
                
                var pcart = JSON.parse(cart);
                
                for(var item in pcart) {

                    if(pcart[item].productid == data.product_id) {

                        
                        pcart[item].quantity = quantity;
                        setCart(localStorage.setItem("cart_item", JSON.stringify(pcart)));
                        toast.success('Cart Update',{position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                        return;
                    }
                }
                
                pcart.push({...pcart.find(p => p.productid != data.product_id), productid:data.product_id,  quantity: quantity,name : data.name,price : data.price,img : data.img})
                setCart(localStorage.setItem("cart_item", JSON.stringify(pcart)));
                toast.success('Product is added to cart',{position: 'bottom-center',style: {background: '#000',color:'#fff'},iconTheme: {primary: 'green',secondary: '#fff',},})
                
            }

         }

    }, [data])
    
  return ('');
}

export default AddToCart;
