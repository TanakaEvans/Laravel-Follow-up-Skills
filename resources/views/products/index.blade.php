@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Add New Product</h3>
            </div>
            <div class="panel-body">
                <form id="productForm" class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Product Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" required>
                            <span class="text-danger name-error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="col-sm-2 control-label">Quantity in Stock</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" min="0" required>
                            <span class="text-danger quantity-error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label">Price per Item</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="price" name="price" placeholder="Price" step="0.01" min="0" required>
                            <span class="text-danger price-error"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary">Add Product</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Product List</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity in Stock</th>
                                <th>Price per Item</th>
                                <th>Datetime Submitted</th>
                                <th>Total Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            @foreach($products as $product)
                            <tr data-id="{{ $product['id'] }}">
                                <td class="product-name">{{ $product['name'] }}</td>
                                <td class="product-quantity">{{ $product['quantity'] }}</td>
                                <td class="product-price">{{ number_format($product['price'], 2) }}</td>
                                <td>{{ $product['submitted_at'] }}</td>
                                <td class="product-total">{{ number_format($product['total_value'], 2) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $product['id'] }}">Edit</button>
                                </td>
                            </tr>
                            <tr class="edit-form" data-id="{{ $product['id'] }}">
                                <td colspan="6">
                                    <form class="edit-product-form form-inline">
                                        <input type="hidden" name="edit_id" value="{{ $product['id'] }}">
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="edit_name_{{ $product['id'] }}" class="sr-only">Product Name</label>
                                            <input type="text" class="form-control" id="edit_name_{{ $product['id'] }}" name="edit_name" value="{{ $product['name'] }}" required>
                                        </div>
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="edit_quantity_{{ $product['id'] }}" class="sr-only">Quantity</label>
                                            <input type="number" class="form-control" id="edit_quantity_{{ $product['id'] }}" name="edit_quantity" value="{{ $product['quantity'] }}" min="0" required>
                                        </div>
                                        <div class="form-group mx-sm-3 mb-2">
                                            <label for="edit_price_{{ $product['id'] }}" class="sr-only">Price</label>
                                            <input type="number" class="form-control" id="edit_price_{{ $product['id'] }}" name="edit_price" value="{{ $product['price'] }}" step="0.01" min="0" required>
                                        </div>
                                        <button type="submit" class="btn btn-success mb-2 update-btn">Update</button>
                                        <button type="button" class="btn btn-default mb-2 cancel-btn">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total:</th>
                                <th id="totalSum">{{ number_format($totalSum, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // CSRF Token setup for AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Add Product Form Submission
        $('#productForm').on('submit', function(e) {
            e.preventDefault();
            $('.loading').show();
            
            // Clear previous error messages
            $('.text-danger').text('');
            
            $.ajax({
                type: 'POST',
                url: '{{ route("products.store") }}',
                data: {
                    name: $('#name').val(),
                    quantity: $('#quantity').val(),
                    price: $('#price').val()
                },
                success: function(response) {
                    if (response.success) {
                        // Reset form
                        $('#productForm')[0].reset();
                        
                        // Update table with all products
                        updateProductTable(response.products, response.totalSum);
                        
                        // Show success message
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        
                        if (errors.name) {
                            $('.name-error').text(errors.name[0]);
                        }
                        if (errors.quantity) {
                            $('.quantity-error').text(errors.quantity[0]);
                        }
                        if (errors.price) {
                            $('.price-error').text(errors.price[0]);
                        }
                    } else {
                        alert('An error occurred. Please try again.');
                    }
                },
                complete: function() {
                    $('.loading').hide();
                }
            });
        });

        // Edit button click
        $(document).on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            $('tr.edit-form[data-id="' + id + '"]').show();
            $(this).closest('tr').hide();
        });

        // Cancel button click
        $(document).on('click', '.cancel-btn', function() {
            var form = $(this).closest('tr.edit-form');
            var id = form.data('id');
            form.hide();
            $('tr[data-id="' + id + '"]:not(.edit-form)').show();
        });

        // Update Product Form Submission
        $(document).on('submit', '.edit-product-form', function(e) {
            e.preventDefault();
            $('.loading').show();
            
            var form = $(this);
            var id = form.find('input[name="edit_id"]').val();
            
            $.ajax({
                type: 'PUT',
                url: '/products/' + id,
                data: {
                    name: form.find('input[name="edit_name"]').val(),
                    quantity: form.find('input[name="edit_quantity"]').val(),
                    price: form.find('input[name="edit_price"]').val()
                },
                success: function(response) {
                    if (response.success) {
                        // Update table with all products
                        updateProductTable(response.products, response.totalSum);
                        
                        // Show success message
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again.');
                },
                complete: function() {
                    $('.loading').hide();
                }
            });
        });

        // Function to update product table
        function updateProductTable(products, totalSum) {
            var tableBody = $('#productTableBody');
            tableBody.empty();
            
            $.each(products, function(index, product) {
                var row = $('<tr data-id="' + product.id + '"></tr>');
                row.append('<td class="product-name">' + product.name + '</td>');
                row.append('<td class="product-quantity">' + product.quantity + '</td>');
                row.append('<td class="product-price">' + parseFloat(product.price).toFixed(2) + '</td>');
                row.append('<td>' + product.submitted_at + '</td>');
                row.append('<td class="product-total">' + parseFloat(product.total_value).toFixed(2) + '</td>');
                row.append('<td><button class="btn btn-sm btn-primary edit-btn" data-id="' + product.id + '">Edit</button></td>');
                tableBody.append(row);
                
                // Add edit form row
                var editRow = $('<tr class="edit-form" data-id="' + product.id + '"></tr>');
                var editFormHtml = '<td colspan="6">' +
                    '<form class="edit-product-form form-inline">' +
                    '<input type="hidden" name="edit_id" value="' + product.id + '">' +
                    '<div class="form-group mx-sm-3 mb-2">' +
                    '<label for="edit_name_' + product.id + '" class="sr-only">Product Name</label>' +
                    '<input type="text" class="form-control" id="edit_name_' + product.id + '" name="edit_name" value="' + product.name + '" required>' +
                    '</div>' +
                    '<div class="form-group mx-sm-3 mb-2">' +
                    '<label for="edit_quantity_' + product.id + '" class="sr-only">Quantity</label>' +
                    '<input type="number" class="form-control" id="edit_quantity_' + product.id + '" name="edit_quantity" value="' + product.quantity + '" min="0" required>' +
                    '</div>' +
                    '<div class="form-group mx-sm-3 mb-2">' +
                    '<label for="edit_price_' + product.id + '" class="sr-only">Price</label>' +
                    '<input type="number" class="form-control" id="edit_price_' + product.id + '" name="edit_price" value="' + product.price + '" step="0.01" min="0" required>' +
                    '</div>' +
                    '<button type="submit" class="btn btn-success mb-2 update-btn">Update</button>' +
                    '<button type="button" class="btn btn-default mb-2 cancel-btn">Cancel</button>' +
                    '</form>' +
                    '</td>';
                editRow.append(editFormHtml);
                tableBody.append(editRow);
            });
            
            // Update total sum
            $('#totalSum').text(parseFloat(totalSum).toFixed(2));
        }
    });
</script>
@endsection
