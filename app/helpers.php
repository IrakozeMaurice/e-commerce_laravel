<?php

// function presentPrice($price)
// {
//     return number_format($price);
// }

function setActiveCategory($category, $output = 'active')
{
    return request()->category == $category ? $output : '';
}
