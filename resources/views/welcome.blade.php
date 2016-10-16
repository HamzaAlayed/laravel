<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: left;
            }

            .title {
                font-size: 24px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <div class="title m-b-md">
                    Product Form
                </div>
                <form class="form-inline" action="/save" id="products">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="product_name">Product name :</label>
                        <input type="text" name="product_name" required id="product_name"/>
                    </div>
                    <div class="form-group">
                        <label for="product_quantity">Quantity in stock:</label>
                        <input type="number" name="product_quantity" required id="product_quantity"/>
                    </div>
                    <div class="form-group">
                        <label for="product_price"> Price per item:</label>
                        <input type="text" name="product_price" required id="product_price"/>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-default">Submit</button>
                        </div>
                    </div>
                </form>
                <div class="title m-b-md">
                    Product List
                </div>
                <table class="table table-striped" id="products_list">
                    <thead>
                    <tr>
                        <td>Product id</td>
                        <td>Product name</td>
                        <td>Quantity in stock</td>
                        <td>Price per item</td>
                        <td>Datetime submitted</td>
                        <td>Total value number</td>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>


        </div>

    </body>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.2/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script lang="javascript">
        $(document).ready(function(){
            $("#products").submit(function(){
                var form=$(this);
                $.ajax({
                    url: '/save',
                    data: form.serialize(),
                    dataType: "json",
                    type:"GET"
                }).done(function(data) {
                    if(data.success){

                        var quantities= 0,price=parseFloat(form.find('[name="product_price"]').val()),
                            quantity=parseInt(form.find('[name="product_quantity"]').val());
                        $("#products_list tbody")
                                .prepend(
                                        $("<tr>")
                                                .append($("<td>").html(form.find('[name="product_name"]').val()))
                                                .append($("<td>").html(form.find('[name="product_quantity"]').val()))
                                                .append($("<td>").html(form.find('[name="product_price"]').val()))
                                                .append($("<td class='total'>").html(price*quantity))
                                );
                        if($("#products_list tbody tr").length==1){
                            $("#products_list tbody")
                                    .append(
                                            $("<tr>")
                                                    .append($("<td>").html(""))
                                                    .append($("<td>").html(""))
                                                    .append($("<td>").html(""))
                                                    .append($("<td id='total'>").html(price*quantity))

                                    );
                        }

                        $(".total").each(function(){
                            quantities+=parseFloat($(this).text());
                        });
                        $("#total").text(quantities)

                    }


                });

               return false;
            });


        });
        $.ajax({
            url: '/get',
            type:"GET"
        }).done(function(data) {
            var products=JSON.parse(data),quantity=0;
        $.each(products,function(key,val){
            var total=parseInt(val.product_quantity)*parseFloat(val.product_price);
            quantity+=total;
            var date = new Date(val.added_date*1000);

            var hours = date.getHours();

            var minutes = "0" + date.getMinutes();

            var seconds = "0" + date.getSeconds();

            var formattedTime = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDate();
             formattedTime += " "+hours + ':' + minutes.substr(-2) + ':' + seconds.substr(-2);
            $("#products_list tbody")
                    .append(
                            $("<tr>")
                                    .append($("<td>").html(val.added_date))
                                    .append($("<td>").html(val.product_name))
                                    .append($("<td>").html(val.product_quantity))
                                    .append($("<td>").html(val.product_price))
                                    .append($("<td>").html(formattedTime))
                                    .append($("<td class='total'>").html(total))
                    );
        });

            $("#products_list tbody")
                    .append(
                            $("<tr>")
                                    .append($("<td>").html(""))
                                    .append($("<td>").html(""))
                                    .append($("<td>").html(""))
                                    .append($("<td>").html(""))
                                    .append($("<td>").html(""))
                                    .append($("<td id='total'>").html(quantity))

                    );



        });
    </script>
</html>
