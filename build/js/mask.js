function mask(str,textbox,maxlen,digit_only) {
  var change;
  var original;

  change=0;
  original=str;
  
  //Sprawdzenie, czy tylko liczby
  if (digit_only == 1) {
    var valid=",.0123456789";
    var temp;
    var new_str;
    new_str="";

    for (var i = 0; i < str.length; i++) {
      temp = "" + str.substring(i,i+1);
      if (valid.indexOf(temp) != "-1") {
        //jesli jest liczba
        new_str=new_str+temp;
      }
    }
    str=new_str;
  }

  //Sprawdzenie, czy tylko liczby
  if (digit_only == 2) {
    var valid="0123456789";
    var temp;
    var new_str;
    new_str="";
    

    for (var i = 0; i < str.length; i++) {
      temp = "" + str.substring(i,i+1);
      if (valid.indexOf(temp) != "-1") {
        //jesli jest liczba
        new_str=new_str+temp;
      }
    }
    str=new_str;
  }
  
  //Sprawdzenie, czy tylko liczby
  if (digit_only == 3) {
    var valid="-0123456789";
    var temp;
    var new_str;
    new_str="";
    

    for (var i = 0; i < str.length; i++) {
      temp = "" + str.substring(i,i+1);
      if (valid.indexOf(temp) != "-1") {
        //jesli jest liczba
        new_str=new_str+temp;
      }
    }
    str=new_str;
  }
  
  //Sprawdzenie, czy tylko liczby
  if (digit_only == 4) {
    var valid="0123456789abcdefghijklmnopqrstuwvxyzABCDEFGHIJKLMNOPQRSTUWXVYZ_";
    var temp;
    var new_str;
    new_str="";
    
    for (var i = 0; i < str.length; i++) {
      temp = "" + str.substring(i,i+1);
      if (valid.indexOf(temp) != "-1") {
        //jesli jest liczba
        new_str=new_str+temp;
      }
    }
    str=new_str;
  }
  
  //Sprawdzenie, czy tylko liczby
  if (digit_only == 5) {
    var valid="0123456789";
    var temp;
    var new_str;
    new_str="";

    for (var i = 0; i < str.length; i++) {
      temp = "" + str.substring(i,i+1);
      if (valid.indexOf(temp) != "-1") {
        new_str=new_str+temp;
      }
    }
    str=new_str;
  }
  
  if (digit_only == 6) {
    var valid="/abcdefghijklmnopqrstuwvxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+-_*.,;@|#$%&()[]{}";
    var temp;
    var new_str;
    new_str="";

    for (var i = 0; i < str.length; i++) {
      temp = "" + str.substring(i,i+1);
      if (valid.indexOf(temp) != "-1") {
        new_str=new_str+temp;
      }
    }
    str=new_str;
  }
  
   if (digit_only == 7)
  {
    var valid="ąęółżźćńĄĘÓŁŻŹĆŃśŚ \'\"/abcdefghijklmnopqrstuwvxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+-_*.,:;@|#$%&()[]{}?!";
    var temp;
    var new_str;
    new_str="";
    

    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      if (valid.indexOf(temp) != "-1")
      {
        //jesli jest liczba
        new_str=new_str+temp;
      }
    }
    str=new_str;
  }
  
  if (digit_only == 8)
  {
    var valid="ąęółżźćńĄĘÓŁŻŹĆŃśŚ \'\"abcdefghijklmnopqrstuwvxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789+-_*.,;@|#$%&()[]{}?!/";
    var temp;
    var new_str;
    new_str="";
    

    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      if (valid.indexOf(temp) != "-1")
      {
        //jesli jest liczba
        new_str=new_str+temp;
      }
    }
    str=new_str;
  }
  
  if (digit_only == 9) {
    var valid=".0123456789";
    var temp;
    var new_str;
    var is_dot;
    
    is_dot = 0;
    
    new_str="";
    

    for (var i = 0; i < str.length; i++) {
      temp = "" + str.substring(i,i+1);
      //zamien , na .
      if (temp == ",") {
        temp = ".";
      }
     
      //sprawdz, czy jest juz drugi .
      if (temp == ".") {
        is_dot = is_dot +1;
      
        if (is_dot>1) {
          temp = "";
        }
      }
      
      if (valid.indexOf(temp) != "-1") {
        new_str=new_str+temp;
      }
    }
    str=new_str;
    if (str.length == 0) {
      str="0";
    }
  }
  
  if (digit_only == 10) {
    var valid="-.0123456789";
    var temp;
    var new_str;
    var is_dot;
    
    is_dot = 0;
    
    new_str="";
    

    for (var i = 0; i < str.length; i++) {
      temp = "" + str.substring(i,i+1);
      //zamien , na .
      if (temp == ",") {
        temp = ".";
      }
     
      //sprawdz, czy jest juz drugi .
      if (temp == ".") {
        is_dot = is_dot +1;
      
        if (is_dot>1) {
          temp = "";
        }
      }
      
      if (valid.indexOf(temp) != "-1") {
        new_str=new_str+temp;
      }
    }
    str=new_str;
    if (str.length == 0) {
      str="0";
    }
  }
  
  if (digit_only == "digit_dot") {
    var valid=".0123456789";
    var temp;
    var new_str;
    var is_dot;
    
    is_dot = 0;
    new_str="";

    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      //zamien , na .
      if (temp == ",")
      {
        temp = ".";
      }
     
      //sprawdz, czy jest juz drugi .
      if (temp == ".")
      {
        is_dot = is_dot +1;
      
        if (is_dot>1)
        {
          temp = "";
        }
      }
      
      if (valid.indexOf(temp) != "-1")
      {
        new_str=new_str+temp;
      }
    }
    str=new_str;
    if (str.length == 0)
    {
      str="0";
    }
  }
  
  if (digit_only == "digit")
  {
    var valid="0123456789";
    var temp;
    var new_str;
    var is_dot;
    
    is_dot = 0;
    new_str="";

    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      //zamien , na .
      if (temp == ",")
      {
        temp = ".";
      }
     
      //sprawdz, czy jest juz drugi .
      if (temp == ".")
      {
        is_dot = is_dot +1;
      
        if (is_dot>1)
        {
          temp = "";
        }
      }
      
      if (valid.indexOf(temp) != "-1")
      {
        new_str=new_str+temp;
      }
    }
    str=new_str;
    if (str.length == 0)
    {
      str="0";
    }
  }
  
  if (digit_only == "digit_dot_null")
  {
    var valid=".0123456789";
    var temp;
    var new_str;
    var is_dot;
    
    is_dot = 0;
    new_str="";

    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      //zamien , na .
      if (temp == ",")
      {
        temp = ".";
      }
     
      //sprawdz, czy jest juz drugi .
      if (temp == ".")
      {
        is_dot = is_dot +1;
      
        if (is_dot>1)
        {
          temp = "";
        }
      }
      
      if (valid.indexOf(temp) != "-1")
      {
        new_str=new_str+temp;
      }
    }
    str=new_str;
    
  }
  
  if (digit_only == "digit_null")
  {
    var valid="0123456789";
    var temp;
    var new_str;
    var is_dot;
    
    is_dot = 0;
    new_str="";

    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      
      if (valid.indexOf(temp) != "-1")
      {
        new_str=new_str+temp;
      }
    }
    str=new_str;
    
  }
  
  if (digit_only == "digit_dot_null_minus")
  {
    var valid=".-0123456789";
    var temp;
    var new_str;
    var is_dot;
    
    is_dot = 0;
    is_minus = 0;
    
    new_str="";

    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      //zamien , na .
      if (temp == ",")
      {
        temp = ".";
      }
     
      //sprawdz, czy jest juz drugi .
      if (temp == ".")
      {
        is_dot = is_dot +1;
      
        if (is_dot>1)
        {
          temp = "";
        }
      }
      
      if (temp == "-")
      {
        is_minus = is_minus +1;
      
        if (is_minus>1 || i>0)
        {
          temp = "";
        }
      }
      
      if (valid.indexOf(temp) != "-1")
      {
        new_str=new_str+temp;
      }
    }
    str=new_str;
    
  }
  
  if (digit_only == "digit_null_minus")
  {
    var valid="-0123456789";
    var temp;
    var new_str;
    var is_dot;
    
    is_dot = 0;
    is_minus = 0;
    
    new_str="";

    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      
      if (temp == "-")
      {
        is_minus = is_minus +1;
      
        if (is_minus>1 || i>0)
        {
          temp = "";
        }
      }
      
      if (valid.indexOf(temp) != "-1")
      {
        new_str=new_str+temp;
      }
    }
    str=new_str;
    
  }
  
  if (digit_only == "current_time") {
    var valid="0123456789";
    var temp;
    var new_str;
    var new_str2;
    var is_dot;
    
    is_dot = 0;
    new_str="";
    new_str2="";
    
    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      
      if (valid.indexOf(temp) != "-1")
      {
        new_str=new_str+temp;
      } 
      
      if (i == 1) new_str=new_str+":";
      if (i == 4) new_str=new_str+":";
    }
    
    str=new_str;
    
  }
  
  //Sprawdzenie, czy tylko liczby
  if (digit_only == "product_count") {
    var valid="0123456789";
    var temp;
    var new_str;
    var is_dot;
    
    is_dot = 0;
    
    new_str="";
    

    for (var i = 0; i < str.length; i++)
    {
      temp = "" + str.substring(i,i+1);
      //zamien , na .
      if (temp == ",")
      {
        temp = ".";
      }
     
      //sprawdz, czy jest juz drugi .
      if (temp == ".")
      {
        is_dot = is_dot +1;
      
        if (is_dot>1)
        {
          temp = "";
        }
      }
      
      if (valid.indexOf(temp) != "-1")
      {
        new_str=new_str+temp;
      }
    }
    str=new_str;
    
    if (str == "0") str="1";
    if (str.length == 0)
    {
      str="1";
    }
  }
  
  //obciecie do maksymalnej dlugosci
  if (str.length > maxlen)
  {
    str=str.substring(0,maxlen);
  }
  
  if (original!=str) change=1;
  
  if (change==1)
  {
    textbox.value = str;
  }
  
}