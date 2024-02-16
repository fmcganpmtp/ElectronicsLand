<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'App\Http\Controllers\FrontEnd\PagesController@HomePage')->name('home');

Auth::routes();
  
Route::get('/login/admin', 'App\Http\Controllers\Auth\LoginController@showAdminLoginForm')->name('admin.login.form');
Route::get('/login/user', 'App\Http\Controllers\Auth\LoginController@showUserLoginForm')->name('user-login');
//Route::get('/admin/login', 'App\Http\Controllers\Auth\LoginController@showAdminLoginForm');
Route::get('/register/admin', 'App\Http\Controllers\Auth\RegisterController@showAdminRegisterForm');
Route::get('/register/user', 'App\Http\Controllers\Auth\RegisterController@showUserRegisterForm')->name('user-register');
Route::post('/logout', 'App\Http\Controllers\Auth\RegisterController@logout')->name('logout');

Route::post('/login/admin', 'App\Http\Controllers\Auth\LoginController@adminLogin')->name('admin.login');
Route::post('/login/user', 'App\Http\Controllers\Auth\LoginController@userLogin');
Route::post('/register/admin', 'App\Http\Controllers\Auth\RegisterController@createAdmin');
Route::post('/register/user', 'App\Http\Controllers\Auth\RegisterController@createUser');
Route::get('/autocomplete-search', 'App\Http\Controllers\FrontEnd\PagesController@autocompleteSearch')->name('autocompleteSearch');
Route::get('/TypeaheadSearch', 'App\Http\Controllers\FrontEnd\PagesController@TypeaheadSearch')->name('TypeaheadSearch');
//Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->name('logout');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('admin.logout');

 //Frontend password reset forgot password
Route::get('/forgot-password', 'App\Http\Controllers\Auth\LoginController@showForgetPasswordForm')->name('forget.password.get');
Route::post('/forgot-password', 'App\Http\Controllers\Auth\LoginController@submitForgetPasswordForm')->name('forget.password.post'); 
Route::get('/reset-password/{token}', 'App\Http\Controllers\Auth\LoginController@showResetPasswordForm')->name('reset.password.get');
Route::post('reset-password', 'App\Http\Controllers\Auth\LoginController@submitResetPasswordForm')->name('reset.password.post');

//chat
Route::post('/get-my-contacts','App\Http\Controllers\FrontEnd\MessageController@getMyContacts')->name('chat.getMyContacts');
Route::get('/getMyMessages/{id}','App\Http\Controllers\FrontEnd\MessageController@getMyMessages')->name('chat.getMyMessages');
Route::post('/sentMessages', 'App\Http\Controllers\FrontEnd\MessageController@sentMessages')->name('chat.sentMessages');
Route::get('/checkNewMessage/{senter_id}/{last_message}', 'App\Http\Controllers\FrontEnd\MessageController@checkNewMessage')->name('chat.checkNewMessage');
Route::get('/checkNotifications', 'App\Http\Controllers\FrontEnd\MessageController@checkNotifications')->name('checkNotifications');

//stripe
Route::post('/session', 'App\Http\Controllers\FrontEnd\StripeController@session')->name('session');
Route::get('/stripe', 'App\Http\Controllers\StripeController@checkout')->name('checkout');
Route::get('/success', 'App\Http\Controllers\FrontEnd\StripeController@success')->name('success');

//newsletter subsription
Route::post('/newsletter-subscription', 'App\Http\Controllers\FrontEnd\ProfileController@NewsletterSubscription')->name('newsletter.subscription'); 

Route::get('buildmenustructure', 'App\Http\Controllers\FrontEnd\PagesController@buildmenustructure')->name('buildmenustructure');

Route::view('/home', 'home');
Route::view('/user', 'user');
//Excel import
Route::post('/seller-products', 'App\Http\Controllers\FrontEnd\SellerController@import')->name('import-exl');

Route::get('/product-details/{productId}', 'App\Http\Controllers\FrontEnd\ProductController@ProductDetails')->name('product.details');
Route::get('autocompleteSproductFrontend', 'App\Http\Controllers\FrontEnd\ProductController@autocompleteSproductFrontend')->name('autocompleteSproductFrontend');
Route::get('/list-categories/{categoryId}', 'App\Http\Controllers\FrontEnd\CategoryController@ListCategories')->name('list.categories');

//Seller Products
Route::get('/seller-products', 'App\Http\Controllers\FrontEnd\SellerController@SellerProductList')->name('seller.products')->middleware('auth:user');



Route::post('/extended_id-search', 'App\Http\Controllers\FrontEnd\ProductController@extendedidSearch')->name('extended_id.search');
Route::post('/loadsubcategory', 'App\Http\Controllers\FrontEnd\ProductController@loadsubcategory')->name('loadsubcategory');


Route::get('/headsearch', 'App\Http\Controllers\FrontEnd\PagesController@headsearch')->name('headsearch');



Route::get('getSellerproductlistFrontEnd', 'App\Http\Controllers\FrontEnd\SellerController@getSellerproductlist')->name('getSellerproductlistFrontEnd')->middleware('auth:user');
Route::get('/getSPendingProductlist', 'App\Http\Controllers\FrontEnd\SellerController@getSPendingProductlist')->name('getSPendingProductlist');
Route::get('/add-seller-product', 'App\Http\Controllers\FrontEnd\SellerController@AddSellerProduct')->name('add.seller.product')->middleware('auth:user');
Route::get('/add-existing-product', 'App\Http\Controllers\FrontEnd\SellerController@AddUsingExistingProduct')->name('existing.product')->middleware('auth:user');
Route::post('updateuserproductvisibility', 'App\Http\Controllers\FrontEnd\SellerController@updateselerproductvisibility')->name('updateuserproductvisibility')->middleware('auth:user');
Route::get('/edit-seller-product/{productId}', 'App\Http\Controllers\FrontEnd\SellerController@EditSellerProduct')->name('edit.sellerProduct')->middleware('auth:user');
Route::get('/edit-pending-seller-product/{productId}', 'App\Http\Controllers\FrontEnd\SellerController@EditPendingSellerProduct')->name('edit.pending.sellerProduct')->middleware('auth:user');
Route::get('/seller-product-detail/{productId}', 'App\Http\Controllers\FrontEnd\SellerController@SellerProductDetails')->name('view.Sproduct');
Route::get('/seller-pending-product-detail/{productId}', 'App\Http\Controllers\FrontEnd\SellerController@SellerPendingProductDetails')->name('view.pending.Sproduct');

Route::get('/add-product-request', 'App\Http\Controllers\FrontEnd\SellerController@AddProductRequest')->name('add.productrequest')->middleware('auth:user');
Route::post('insertproductrequest', 'App\Http\Controllers\FrontEnd\SellerController@insertproductrequest')->name('insertproductrequest');
Route::get('product-requests/{search_key?}', 'App\Http\Controllers\FrontEnd\SellerController@ProductRequests')->name('Product.Requests')->middleware('auth:user');
Route::post('/product-request-search', 'App\Http\Controllers\FrontEnd\SellerController@ProuctRquestSearch')->name('ProuctRquestSearch');

Route::get('my-product-requests/{search_key?}', 'App\Http\Controllers\FrontEnd\SellerController@MyProductRequests')->name('MyProduct.Requests')->middleware('auth:user');
Route::post('/my-product-request-search', 'App\Http\Controllers\FrontEnd\SellerController@MyProuctRquestSearch')->name('MyProuctRquestSearch');
Route::get('/deleteProductRequest/{req_id}','App\Http\Controllers\FrontEnd\SellerController@deleteProductRequest')->name('deleteProductRequest');
Route::get('/extendrequest/{req_id}','App\Http\Controllers\FrontEnd\SellerController@ExtendRequest')->name('ExtendRequest');

Route::get('/edit-product-request/{req_id}','App\Http\Controllers\FrontEnd\SellerController@EditProductRequest')->name('edit.productrequest')->middleware('auth:user');
Route::post('/update-ProductRequest','App\Http\Controllers\FrontEnd\SellerController@updateProductRequest')->name('update.ProductRequest');

Route::get('admin/productrequest','App\Http\Controllers\Admin\ProductController@listProductRequest')->name('list.ProductRequest')->middleware('auth:admin');
Route::get('getProductRequest','App\Http\Controllers\Admin\ProductController@getProductRequest')->name('getProductRequest')->middleware('auth:admin');
Route::get('/admin-extendrequest/{req_id}','App\Http\Controllers\Admin\ProductController@ExtendRequest')->name('AdminExtendRequest');
Route::get('/admin-deleteProductRequest/{req_id}','App\Http\Controllers\Admin\ProductController@deleteProductRequest')->name('AdmindeleteProductRequest');
Route::get('admin/view-product-request/{req_id}','App\Http\Controllers\Admin\ProductController@viewProductRequest')->name('view.productrequest')->middleware('auth:admin');

Route::get('/add-to-wishlist', 'App\Http\Controllers\FrontEnd\SellerController@AddToWishlist')->name('addToWishlist');
Route::get('/remove-wishlist', 'App\Http\Controllers\FrontEnd\SellerController@RemoveWishlist')->name('RemoveWishlist');

Route::get('/wishlist-items', 'App\Http\Controllers\FrontEnd\SellerController@WishlistItems')->name('WishlistItems')->middleware('auth:user');
Route::post('/wishlist-items', 'App\Http\Controllers\FrontEnd\SellerController@WishlistItemsFilter')->name('WishlistItemsFilter')->middleware('auth:user');

Route::delete('/deleteSellerimage','App\Http\Controllers\FrontEnd\SellerController@deleteSellerimage')->name('delete.SproductImage');
Route::post('/deletePendingSellerimage','App\Http\Controllers\FrontEnd\SellerController@deletePendingSellerimage')->name('delete.PendingSproductImage');
Route::get('/admin/products/delete-Sproduct/{productId}','App\Http\Controllers\FrontEnd\SellerController@deleteSProduct')->name('delete.Sproduct')->middleware('auth:user');
Route::get('/admin/products/delete-pendingSproduct/{productId}','App\Http\Controllers\FrontEnd\SellerController@deletePendingSProduct')->name('delete.PendingSproduct')->middleware('auth:user');


Route::post('/update-seller-product','App\Http\Controllers\FrontEnd\SellerController@updateSellerProduct')->name('update.Sproduct');

Route::post('/update-sellerProduct','App\Http\Controllers\FrontEnd\SellerController@updateSProduct')->name('update.SellerProduct');
Route::post('/update-pending-sellerProduct','App\Http\Controllers\FrontEnd\SellerController@updatePendingSProduct')->name('update.PendingSellerProduct');
Route::get('fetchUserhaveactiveplan','App\Http\Controllers\Admin\SellersController@fetchUserhaveactiveplan')->name('fetchUserhaveactiveplan')->middleware('auth:admin');

//Auto Complete Products
Route::get('getproducts','App\Http\Controllers\FrontEnd\SellerController@autoCompleteProduct')->name('getproducts');
Route::get('fetchProduct', 'App\Http\Controllers\FrontEnd\SellerController@AddProductSellerExisting')->name('fetch.id')->middleware('auth:user');
Route::post('fetchProduct', 'App\Http\Controllers\FrontEnd\SellerController@fetchSubcategory')->name('fetchSubcategory');

Route::get('/ajax-subcat','App\Http\Controllers\FrontEnd\SellerController@ajaxSubcat')->name('ajax.subcat');




//Front End co-seller
Route::get('/co-sellers/lists', 'App\Http\Controllers\FrontEnd\SellerController@List_co_sellers')->name('user.listcosellers')->middleware('auth:user');
Route::post('/add_newCo_seller', 'App\Http\Controllers\FrontEnd\SellerController@addNewSeller')->name('add.addCoSeller')->middleware('auth:user');
Route::post('usersellersstatusupdates','App\Http\Controllers\FrontEnd\SellerController@usersellersstatusupdates')->name('usersellersstatusupdates')->middleware('auth:user'); 
Route::get('/admin/user/delete-co-seller/{userId}','App\Http\Controllers\FrontEnd\SellerController@deleteUser')->name('delete-coceller.user')->middleware('auth:user');

//Insert Seller Products
Route::post('insert-sproduct', 'App\Http\Controllers\FrontEnd\SellerController@InsertSproduct')->name('insert.sellerProduct');
Route::post('addnewSproduct', 'App\Http\Controllers\FrontEnd\SellerController@addnewSproduct')->name('addnewSproduct');

Route::post('loadreview', 'App\Http\Controllers\FrontEnd\SellerController@loadreview')->name('loadreview');

Route::post('loadproducts', 'App\Http\Controllers\FrontEnd\ProfileController@loadproducts')->name('loadproducts');

Route::post('resend_veryfication_email', 'App\Http\Controllers\FrontEnd\ProfileController@resend_veryfication_email')->name('resend_veryfication_email'); 



//Terms and Conditions
Route::get('/terms-and-conditions', 'App\Http\Controllers\FrontEnd\PagesController@TermsAndCondition')->name('terms.condition');
//Privacy Policy
Route::get('/privacy-policy', 'App\Http\Controllers\FrontEnd\PagesController@PrivacyPolicy')->name('privacy.policy');



Route::get('/admin/settings/topcategory', 'App\Http\Controllers\Admin\AdminController@topcategorylist')->name('admin.listtopcategory')->middleware('auth:admin');
Route::post('/admin/settings/topcategory','App\Http\Controllers\Admin\AdminController@savetopcategory')->name('admin.savetopcategory')->middleware('auth:admin');

Route::get('/refund-policy', 'App\Http\Controllers\FrontEnd\PagesController@RefundPolicy')->name('refund.policy');
Route::get('/cookie-policy', 'App\Http\Controllers\FrontEnd\PagesController@CookiePolicy')->name('cookie.policy');
Route::get('/about-us', 'App\Http\Controllers\FrontEnd\PagesController@AboutUs')->name('about.us');
Route::get('/seller-instructions', 'App\Http\Controllers\FrontEnd\PagesController@SellerInstructions')->name('seller.instruction');
Route::get('/buyer-instructions', 'App\Http\Controllers\FrontEnd\PagesController@BuyerInstructions')->name('buyer.instruction');
Route::get('/contact-us', 'App\Http\Controllers\FrontEnd\PagesController@ContactUs')->name('contactus');
Route::post('/contact-submit', 'App\Http\Controllers\FrontEnd\PagesController@ContactSubmit')->name('contact.submit');

Route::get('/product-detail/{productId}', 'App\Http\Controllers\FrontEnd\ProductController@ProductDetails')->name('view.productDetail');
Route::post('/review-submit', 'App\Http\Controllers\FrontEnd\ProductController@ReviewSubmit')->name('review.submit');
Route::get('/seller-message', 'App\Http\Controllers\FrontEnd\ProductController@SelleMessage')->name('seller.message');

Route::get('/buyer-contact-request', 'App\Http\Controllers\FrontEnd\ProfileController@BuyerContactRequest')->name('BuyerContactRequest')->middleware('auth:user');;

//product listing 
Route::get('product-listing/{search_key?}', 'App\Http\Controllers\FrontEnd\ProductController@ProductListing')->name('Product.Listing');

// Front end packages listing page
Route::get('/package-listing', 'App\Http\Controllers\FrontEnd\PackageController@PackageListing')->name('package.listing')->middleware('auth:user');
Route::get('/package-detail', 'App\Http\Controllers\FrontEnd\PackageController@PackageDetails')->name('package.details')->middleware('auth:user');
Route::get('/PackgeInvoice', 'App\Http\Controllers\FrontEnd\PackageController@PackgeInvoice')->name('PackgeInvoice')->middleware('auth:user');
Route::get('/PackgeInvoice', 'App\Http\Controllers\FrontEnd\PackageController@PackgeInvoice')->name('PackgeInvoice')->middleware('auth:user');
Route::get('/prnpriview','App\Http\Controllers\FrontEnd\PackageController@prnpriview')->name('prnpriview')->middleware('auth:user');

Route::get('/cart', 'App\Http\Controllers\FrontEnd\PackageController@Cart')->name('cart')->middleware('auth:user');
Route::get('/subscription-checkout', 'App\Http\Controllers\FrontEnd\PackageController@SubscriptionCheckout')->name('subscription.checkout')->middleware('auth:user');



Route::get('/package-detail1', 'App\Http\Controllers\FrontEnd\PackageController@PackageDetails1')->name('package.details1')->middleware('auth:user');


//User purchases subsription packages
Route::get('/subscription-details', 'App\Http\Controllers\FrontEnd\PackageController@SubscriptionDetails')->name('subscription.details')->middleware('auth:user');
//Upgarde package
Route::get('/upgrade-package', 'App\Http\Controllers\FrontEnd\PackageController@UpgradePackage')->name('upgrade.package')->middleware('auth:user');

//Expired package
Route::get('/expired-package', 'App\Http\Controllers\FrontEnd\PackageController@ExpiredPackage')->name('expired.package')->middleware('auth:user');

//Renew active package
Route::get('/renew-package', 'App\Http\Controllers\FrontEnd\PackageController@RenewPackage')->name('renew.package')->middleware('auth:user');
//package checkout submission
Route::post('/checkout-submit', 'App\Http\Controllers\FrontEnd\PackageController@submitCheckout')->name('checkout.submit');
Route::get('/checkout-submit', 'App\Http\Controllers\FrontEnd\PackageController@submitCheckout')->name('checkout.submit')->middleware('auth:user');;
Route::post('/cart-submit', 'App\Http\Controllers\FrontEnd\PackageController@submitCart')->name('cart.submit');
Route::get('/order-success', 'App\Http\Controllers\FrontEnd\PackageController@OrderSuccess')->name('order.success')->middleware('auth:user');

// Create Seller Profile
Route::get('/create-seller-profile', 'App\Http\Controllers\FrontEnd\ProfileController@CreateSellerProfile')->name('create.seller.profile')->middleware('auth:user');
Route::post('/update-seller', 'App\Http\Controllers\FrontEnd\ProfileController@UpdateSeller')->name('update.seller');
Route::post('/update-company', 'App\Http\Controllers\FrontEnd\ProfileController@UpdateCompany')->name('update.company');

//Profile Pages
Route::get('/seller-profile', 'App\Http\Controllers\FrontEnd\ProfileController@SellerProfile')->name('seller.profile')->middleware('auth:user');
Route::get('/seller-dashboard', 'App\Http\Controllers\FrontEnd\ProfileController@SellerDashboard')->name('seller.dashboard')->middleware('auth:user');
Route::get('/buyer-dashboard', 'App\Http\Controllers\FrontEnd\ProfileController@BuyerDashboard')->name('buyer.dashboard')->middleware('auth:user');
Route::get('/view-profile-buyer', 'App\Http\Controllers\FrontEnd\ProfileController@ViewProfileBuyer')->name('ViewProfileBuyer')->middleware('auth:user');	
Route::get('/business-insight', 'App\Http\Controllers\FrontEnd\ProfileController@BusinessInsight')->name('BusinessInsight')->middleware('auth:user');
Route::get('/seller-product-pending-approval', 'App\Http\Controllers\FrontEnd\ProfileController@SellerProductPendingApprovals')->name('SellerProductPendingApprovals');
Route::get('/getSellerPendingProductlist', 'App\Http\Controllers\FrontEnd\ProfileController@getSellerPendingProductlist')->name('getSellerPendingProductlist');

Route::get('getbusinessproducts','App\Http\Controllers\FrontEnd\ProfileController@getbusinessproducts')->name('getbusinessproducts');
Route::get('getbusinessCategories','App\Http\Controllers\FrontEnd\ProfileController@getbusinessCategories')->name('getbusinessCategories');

Route::get('/view-profile-guest', 'App\Http\Controllers\FrontEnd\ProfileController@ViewProfileGuest')->name('ViewProfileGuest')->middleware('auth:user');	
Route::get('/view-seller-profile/{profId}', 'App\Http\Controllers\FrontEnd\ProfileController@ViewSellerProfile')->name('ViewSeller.profile')->middleware('auth:user');
Route::get('/view-profile-seller', 'App\Http\Controllers\FrontEnd\ProfileController@ViewProfileSeller')->name('ViewProfileSeller')->middleware('auth:user');
Route::get('/buyer-profile', 'App\Http\Controllers\FrontEnd\ProfileController@BuyerProfile')->name('buyer.profile')->middleware('auth:user');
Route::get('/guest-profile', 'App\Http\Controllers\FrontEnd\ProfileController@GuestProfile')->name('guest.profile')->middleware('auth:user');
Route::get('profile_to_network', 'App\Http\Controllers\FrontEnd\ProfileController@profile_to_network')->name('profile_to_network')->middleware('auth:user');

Route::post('userproductbulkdelete','App\Http\Controllers\FrontEnd\SellerController@userproductbulkdelete')->name('userproductbulkdelete')->middleware('auth:user');
//mynetwork
Route::get('/my-network', 'App\Http\Controllers\FrontEnd\PagesController@mynetwork')->name('user.mynetwork')->middleware('auth:user');

Route::post('sellers_list', 'App\Http\Controllers\FrontEnd\PagesController@getsellerslist_search')->name('sellers.get-more-sellers');
Route::post('network_contacts', 'App\Http\Controllers\FrontEnd\PagesController@getnetwork_users_list')->name('sellers.network-contacts');
Route::get('/revokeFrom_network', 'App\Http\Controllers\FrontEnd\PagesController@revokeFrom_network')->name('revokeFrom_network')->middleware('auth:user');

Route::post('getnetwork_users_list', 'App\Http\Controllers\FrontEnd\PagesController@getnetwork_users_list')->name('getnetwork_users_list')->middleware('auth:user');



Route::post('getsellerslist_search', 'App\Http\Controllers\FrontEnd\PagesController@getsellerslist_search')->name('getsellerslist_search')->middleware('auth:user');

Route::post('updatedeletedcontact', 'App\Http\Controllers\FrontEnd\MessageController@hide_chat_contact')->name('updatedeletedcontact')->middleware('auth:user');

Route::get('/online-status', 'App\Http\Controllers\FrontEnd\ProfileController@show')->name('status.show')->middleware('auth:user');
// buyer Profile Edit
Route::get('/edit-buyer-profile', 'App\Http\Controllers\FrontEnd\ProfileController@EditBuyerProfile')->name('edit.buyer.profile')->middleware('auth:user');
Route::get('/edit-guest-profile', 'App\Http\Controllers\FrontEnd\ProfileController@EditGuestProfile')->name('edit.guest.profile')->middleware('auth:user');

Route::post('/update-buyer', 'App\Http\Controllers\FrontEnd\ProfileController@UpdateBuyer')->name('update.buyer');
Route::post('/update-guest', 'App\Http\Controllers\FrontEnd\ProfileController@UpdateGuest')->name('update.guest');

// Seller KYC approval
Route::get('/kyc-approval', 'App\Http\Controllers\FrontEnd\ProfileController@SellerKYCApproval')->name('seller.kyc.approval')->middleware('auth:user');
Route::post('/send-kyc-mail', 'App\Http\Controllers\FrontEnd\ProfileController@SendKYCMail')->name('send.kyc.mail');
Route::post('/sendBuyerApprovalMail', 'App\Http\Controllers\FrontEnd\ProfileController@sendBuyerApprovalMail')->name('sendBuyerApprovalMail');

// email status update  after mail check
Route::get('/approved-kyc/{token}', 'App\Http\Controllers\FrontEnd\ProfileController@ApprovedKYC')->name('approved.kyc');
Route::get('/ApprovedBuyerEmail/{token}', 'App\Http\Controllers\FrontEnd\ProfileController@ApprovedBuyerEmail')->name('ApprovedBuyerEmail');
Route::post('update-kyc-doc','App\Http\Controllers\FrontEnd\ProfileController@UpdateKycDoc')->name('update.kyc.doc');


//Ajax profile image update
Route::post('update-image','App\Http\Controllers\FrontEnd\ProfileController@update_image')->name('update-image');
//Ajax Company image update
Route::post('update-company-image','App\Http\Controllers\FrontEnd\ProfileController@UpdateCompanyImage')->name('update.company.image');

// buyer Profile Edit
Route::get('/edit-seller-profile/{user_id}', 'App\Http\Controllers\FrontEnd\ProfileController@EditSellerProfile')->name('edit.seller.profile')->middleware('auth:user');
Route::post('/update-seller-profile', 'App\Http\Controllers\FrontEnd\ProfileController@UpdateSellerProfile')->name('update.seller.profile');

//Reset password for user
Route::post('reset-user-password', 'App\Http\Controllers\FrontEnd\ProfileController@submitUserResetPasswordForm')->name('user.pw.reset');

Route::get('admin/list-kycs','App\Http\Controllers\Admin\AdminController@ListKycs')->name('list.user.kycs')->middleware('auth:admin');
Route::get('admin/kyc-approve/{userId}','App\Http\Controllers\Admin\AdminController@kycApprove')->name('kyc.approve')->middleware('auth:admin');
Route::post('/update-kyc','App\Http\Controllers\Admin\AdminController@updateKyc')->name('update.kyc')->middleware('auth:admin');

//------------------Admin/Users Settings--
Route::get('/admin', 'App\Http\Controllers\Admin\AdminController@showAdmin')->name('admin.dashboard')->middleware('auth:admin');
Route::get('/admin/users/list', 'App\Http\Controllers\Admin\AdminController@listUsers')->name('list-users')->middleware('auth:admin');
Route::get('/admin/users/create-user', 'App\Http\Controllers\Admin\AdminController@createUser')->name('create.user')->middleware('auth:admin');
Route::post('/save-user','App\Http\Controllers\Admin\AdminController@saveUser')->name('save.user')->middleware('auth:admin');
Route::get('/admin/user/edit-user/{userId}','App\Http\Controllers\Admin\AdminController@editUser')->name('edit.user')->middleware('auth:admin');
Route::post('/update-user','App\Http\Controllers\Admin\AdminController@updateUser')->name('update.user')->middleware('auth:admin');
Route::get('/admin/user/delete-user/{userId}','App\Http\Controllers\Admin\AdminController@deleteUser')->name('delete.user')->middleware('auth:admin');
Route::get('getuserslist', 'App\Http\Controllers\Admin\AdminController@getuserslist')->name('getuserslist')->middleware('auth:admin');
Route::delete('/deleteUserimage/{id}','App\Http\Controllers\Admin\AdminController@deleteUserimage')->name('delete.userImage');
Route::post('/deleteprofileimage','App\Http\Controllers\Admin\AdminController@deleteprofileimage')->name('deleteprofileimage')->middleware('auth:admin');



//--------------- admin Insight Reports
Route::get('/admin/seller/insight-report', 'App\Http\Controllers\Admin\UsersInsightController@index')->name('admin.sellerinsight')->middleware('auth:admin');
Route::get('getsellersinsightlist','App\Http\Controllers\Admin\UsersInsightController@getsellersinsightlist')->name('getsellersinsightlist')->middleware('auth:admin');

Route::get('/admin/buyer/insight-report', 'App\Http\Controllers\Admin\UsersInsightController@buyerindex')->name('admin.buyerinsightreport')->middleware('auth:admin');
Route::get('getbuyersinsightlist','App\Http\Controllers\Admin\UsersInsightController@getbuyersinsightlist')->name('getbuyersinsightlist')->middleware('auth:admin');

//------------------Admin/Permission Settings--
Route::get('admin/permissions','App\Http\Controllers\Admin\AdminController@listPermissions')->name('list.permissions')->middleware('auth:admin');
Route::get('getpermissionslist','App\Http\Controllers\Admin\AdminController@getpermissionslist')->name('getpermissionslist')->middleware('auth:admin');
Route::get('/admin/permissions/create-permission', 'App\Http\Controllers\Admin\AdminController@createPermission')->name('create.permission')->middleware('auth:admin');
Route::post('/save-permission','App\Http\Controllers\Admin\AdminController@savePermission')->name('save.permission')->middleware('auth:admin');
Route::get('/admin/permission/edit-permission/{permissionId}','App\Http\Controllers\Admin\AdminController@editPermission')->name('edit.permission')->middleware('auth:admin');
Route::post('/update-permission','App\Http\Controllers\Admin\AdminController@updatePermission')->name('update.permission')->middleware('auth:admin');
Route::get('/admin/permission/delete-permission/{permissionId}','App\Http\Controllers\Admin\AdminController@deletePermission')->name('delete.permission')->middleware('auth:admin');

//------------ admin dashboard
Route::post('getchat_insight', 'App\Http\Controllers\Admin\AdminController@getchat_insight')->name('getchat_insight')->middleware('auth:admin');
Route::post('get_engagedusers', 'App\Http\Controllers\Admin\AdminController@get_engagedusers')->name('get_engagedusers')->middleware('auth:admin');

//------------------Admin/User Profile--
Route::get('/admin/users/profile', 'App\Http\Controllers\Admin\AdminController@profile')->name('admin.profile')->middleware('auth:admin');
Route::get('removeprofilepic', 'App\Http\Controllers\Admin\AdminController@removeprofilepic')->name('removeprofilepic')->middleware('auth:admin');

//------------------Admin/Change Password--
Route::post('reset-admin-password', 'App\Http\Controllers\Admin\AdminController@submitAdminResetPasswordForm')->name('reset.admin.password.post')->middleware('auth:admin');

//------------------Admin/Admin Roles--
Route::get('admin/admin-roles','App\Http\Controllers\Admin\AdminRoleController@listAdminRoles')->name('list.admin.roles')->middleware('auth:admin');
Route::get('/admin/admin-roles/create-admin-role', 'App\Http\Controllers\Admin\AdminRoleController@createAdminRole')->name('create.admin.role')->middleware('auth:admin');
Route::post('/save-admin-role','App\Http\Controllers\Admin\AdminRoleController@saveAdminRole')->name('save.admin.role')->middleware('auth:admin');
Route::get('/admin/admin-role/edit-admin-role/{adminRoleId}','App\Http\Controllers\Admin\AdminRoleController@editAdminRole')->name('edit.admin.role')->middleware('auth:admin');
Route::post('/update-admin-role','App\Http\Controllers\Admin\AdminRoleController@updateAdminRole')->name('update.admin.role')->middleware('auth:admin');
Route::get('/admin/admin-role/delete-admin-role/{adminRoleId}','App\Http\Controllers\Admin\AdminRoleController@deleteAdminRole')->name('delete.admin.role')->middleware('auth:admin');
Route::get('getrolevalues','App\Http\Controllers\Admin\AdminRoleController@getrolevalues')->name('getrolevalues')->middleware('auth:admin');

//------------------Admin/Company Types-
Route::get('admin/company-type','App\Http\Controllers\Admin\CompanyTypeController@listCompanyTypes')->name('list.company.type')->middleware('auth:admin');
Route::get('/admin/company-type/create-company-type', 'App\Http\Controllers\Admin\CompanyTypeController@createCompanyType')->name('create.company.type')->middleware('auth:admin');
Route::post('/save-company-type','App\Http\Controllers\Admin\CompanyTypeController@saveCompanyType')->name('save.company.type')->middleware('auth:admin');
Route::get('/admin/company-type/edit-company-type/{CompanyTypeId}','App\Http\Controllers\Admin\CompanyTypeController@editCompanyType')->name('edit.company.type')->middleware('auth:admin');
Route::post('/update-company-type','App\Http\Controllers\Admin\CompanyTypeController@updateCompanyType')->name('update.company.type')->middleware('auth:admin');
Route::get('/admin/company-type/delete-company-type/{CompanyTypeId}','App\Http\Controllers\Admin\CompanyTypeController@deleteCompanyType')->name('delete.company.type')->middleware('auth:admin');
Route::get('getcompanytypevalues','App\Http\Controllers\Admin\CompanyTypeController@getCompanyTypevalues')->name('getCompanyTypevalues')->middleware('auth:admin');

//------------------Admin/Guest Users List
Route::get('/admin/guest/list', 'App\Http\Controllers\Admin\GuestUserController@index')->name('admin.guestlist')->middleware('auth:admin');
Route::get('getguestuserslist','App\Http\Controllers\Admin\GuestUserController@getguestuserslist')->name('getguestuserslist')->middleware('auth:admin');
Route::get('/admin/guest/create', 'App\Http\Controllers\Admin\GuestUserController@create')->name('guestprofile.create')->middleware('auth:admin');
 Route::post('/admin/guest/store', 'App\Http\Controllers\Admin\GuestUserController@store')->name('guestprofile.store')->middleware('auth:admin');
Route::get('/admin/guest/view/{id}', 'App\Http\Controllers\Admin\GuestUserController@guestdetails')->name('admin.guestview')->middleware('auth:admin');
Route::get('/admin/guest/edit/{id}', 'App\Http\Controllers\Admin\GuestUserController@guestedit')->name('admin.guestedit')->middleware('auth:admin');
Route::post('/admin/guest/update', 'App\Http\Controllers\Admin\GuestUserController@update')->name('guestprofile.update')->middleware('auth:admin');

					//------------------Admin/Currency-
Route::get('admin/currency','App\Http\Controllers\Admin\CurrencyController@listCurrencies')->name('list.currency')->middleware('auth:admin');
Route::get('/admin/currency/create-currency', 'App\Http\Controllers\Admin\CurrencyController@createCurrencies')->name('create.currency')->middleware('auth:admin');
Route::post('/save-currency','App\Http\Controllers\Admin\CurrencyController@saveCurrencies')->name('save.currency')->middleware('auth:admin');
Route::get('/admin/currency/edit-currency/{CurrenciesId}','App\Http\Controllers\Admin\CurrencyController@editCurrencies')->name('edit.currency')->middleware('auth:admin');
Route::post('/update-currency','App\Http\Controllers\Admin\CurrencyController@updateCurrencies')->name('update.currency')->middleware('auth:admin');
Route::get('/admin/currency/delete-currency/{CurrenciesId}','App\Http\Controllers\Admin\CurrencyController@deleteCurrencies')->name('delete.currency')->middleware('auth:admin');
Route::get('getCurrenciesvalues','App\Http\Controllers\Admin\CurrencyController@getCurrenciesvalues')->name('getCurrenciesvalues')->middleware('auth:admin');



//------------------Admin/Product Category Settings--

Route::get('getcategorylist','App\Http\Controllers\Admin\CategoryController@getcategorylist')->name('getcategorylist')->middleware('auth:admin');
Route::get('/admin/category','App\Http\Controllers\Admin\CategoryController@listCategories')->name('category.list')->middleware('auth:admin');
Route::get('/admin/category/search', 'App\Http\Controllers\Admin\CategoryController@searchCategory')->name('search.category')->middleware('auth:admin');
Route::get('/admin/category/create', 'App\Http\Controllers\Admin\CategoryController@createCategory')->name('createCategory')->middleware('auth:admin');
Route::post('/admin/category/create', 'App\Http\Controllers\Admin\CategoryController@saveCategory')->name('saveCategory')->middleware('auth:admin');
Route::get('/admin/category/edit/{categoryId}', 'App\Http\Controllers\Admin\CategoryController@editCategory')->name('edit.category')->middleware('auth:admin');
Route::get('/admin/category/delete/{categoryId}', 'App\Http\Controllers\Admin\CategoryController@deleteCategory')->name('delete.category')->middleware('auth:admin');
Route::post('/update-category','App\Http\Controllers\Admin\CategoryController@updateCategory')->name('update.category')->middleware('auth:admin');
Route::get('/admin/category/view/{categoryId}','App\Http\Controllers\Admin\CategoryController@viewCategory')->name('view.category')->middleware('auth:admin');
//Route::any('category/create', [CategoryController::class, 'createCategory'])->name('createCategory')->middleware('auth:admin');

// Route::get('/admin/general-settings', 'App\Http\Controllers\Admin\AdminGeneralSettingsController@index')->name('create.settings')->middleware('auth:admin');
// Route::get('/admin/general-settings-list', 'App\Http\Controllers\Admin\AdminGeneralSettingsController@GeneralSettingslist')->name('general-setting.list')->middleware('auth:admin');
// Route::post('/general-setting/insert', 'App\Http\Controllers\Admin\AdminGeneralSettingsController@insert')->name('general-setting.insert')->middleware('auth:admin');
// Route::get('/general-setting/delete/{settingsId}', 'App\Http\Controllers\Admin\AdminGeneralSettingsController@deleteSettings')->name('delete.general-settings')->middleware('auth:admin');
// Route::post('/update-settings','App\Http\Controllers\Admin\AdminGeneralSettingsController@updateSettings')->name('update.settings')->middleware('auth:admin');

//------------------Admin/Pakages Settings--
Route::get('/admin/packages/subscription-packages', 'App\Http\Controllers\Admin\PackageController@listPackages')->name('list.packges')->middleware('auth:admin');
Route::get('getpackagelist', 'App\Http\Controllers\Admin\PackageController@getpackagelist')->name('getpackagelist')->middleware('auth:admin');
Route::get('/admin/packages/create-packages', 'App\Http\Controllers\Admin\PackageController@createPackage')->name('create.package')->middleware('auth:admin');
Route::get('/admin/packages/search', 'App\Http\Controllers\Admin\PackageController@searchPackages')->name('search-packages')->middleware('auth:admin');
Route::post('/save-admin-package','App\Http\Controllers\Admin\PackageController@savePackage')->name('save.admin.package')->middleware('auth:admin');
Route::get('/admin/packages/edit-package/{packageId}','App\Http\Controllers\Admin\PackageController@editPackage')->name('edit.package')->middleware('auth:admin');
Route::post('/update-package','App\Http\Controllers\Admin\PackageController@updatePackage')->name('update.package')->middleware('auth:admin');
Route::get('/admin/packages/delete-package/{packageId}','App\Http\Controllers\Admin\PackageController@deletePackage')->name('delete.package')->middleware('auth:admin');
Route::get('/admin/packages/view/{packageId}','App\Http\Controllers\Admin\PackageController@viewPackage')->name('view.package')->middleware('auth:admin');
Route::post('/slider/packageAccount', 'App\Http\Controllers\Admin\PackageController@packageAccount')->name('remove.packageAccount')->middleware('auth:admin');
Route::get('/admin/packages/subscription/users/list', 'App\Http\Controllers\Admin\PackageController@subscriptionusers')->name('subscription.users')->middleware('auth:admin');
Route::post('subscriptionstatuschange','App\Http\Controllers\Admin\PackageController@subscriptionstatuschange')->name('subscriptionstatuschange');
Route::get('getsubscriptionuserslist', 'App\Http\Controllers\Admin\PackageController@getsubscriptionuserslist')->name('getsubscriptionuserslist')->middleware('auth:admin');
Route::get('/admin/packages/subscription/users-details/list/{id}', 'App\Http\Controllers\Admin\PackageController@subscriptionusersdetails')->name('admin.subscriptionusersdetails')->middleware('auth:admin');
Route::post('ajaxextendexpirydate','App\Http\Controllers\Admin\PackageController@extendexpirydate')->name('ajaxextendexpirydate')->middleware('auth:admin');
Route::get('/admin/packages/senderRemainder/{id}', 'App\Http\Controllers\Admin\PackageController@senderRemainder')->name('admin.senderRemainder')->middleware('auth:admin');

//------------------Admin/Product Settings--
Route::get('/admin/products/list', 'App\Http\Controllers\Admin\ProductController@listProducts')->name('list-products')->middleware('auth:admin');
Route::get('/admin/SellerProducts/list', 'App\Http\Controllers\Admin\ProductController@listSellerProducts')->name('list-Sellerproducts')->middleware('auth:admin');

Route::get('getproductlist', 'App\Http\Controllers\Admin\ProductController@getproductlist')->name('getproductlist')->middleware('auth:admin');
Route::get('getSellerProductlist', 'App\Http\Controllers\Admin\ProductController@getSellerProductlist')->name('getSellerProductlist')->middleware('auth:admin');

Route::get('/admin/products/create-product', 'App\Http\Controllers\Admin\ProductController@createProduct')->name('create.product')->middleware('auth:admin');

Route::post('/save-product','App\Http\Controllers\Admin\ProductController@saveProduct')->name('save.product')->middleware('auth:admin');

Route::get('/admin/products/edit-Sellerproduct/{productId}','App\Http\Controllers\Admin\ProductController@editProduct')->name('edit.Sellerproduct')->middleware('auth:admin');
Route::post('/update-product','App\Http\Controllers\Admin\ProductController@updateProduct')->name('update.product')->middleware('auth:admin');
Route::get('/admin/products/delete-product/{productId}','App\Http\Controllers\Admin\ProductController@deleteProduct')->name('delete.product')->middleware('auth:admin');
Route::get('/admin/products/delete-Sellerproduct/{productId}','App\Http\Controllers\Admin\ProductController@deleteSellerProduct')->name('delete.Sellerproduct')->middleware('auth:admin');
Route::get('/admin/products/view/{productId}','App\Http\Controllers\Admin\ProductController@viewProduct')->name('view.product')->middleware('auth:admin');
Route::get('/admin/Sellerproducts/view/{productId}','App\Http\Controllers\Admin\ProductController@viewSellerProduct')->name('view.Sellerproduct')->middleware('auth:admin');
Route::get('/admin/products/uploadcsvfile','App\Http\Controllers\Admin\ProductController@uploadcsvfile')->name('product.csvfileupload')->middleware('auth:admin');


//Excel import
Route::post('/admin-import-excel', 'App\Http\Controllers\Admin\ProductController@import')->name('admin.import-exl');
Route::post('/admin-import-product-excel', 'App\Http\Controllers\Admin\ProductController@adminimport')->name('admin.import-product-exl');

Route::get('/admin/autocomplete/seller','App\Http\Controllers\Admin\ProductController@autoComplateSeller')->name('autocomplete.seller');
Route::get('/admin/autocomplete/seller_company','App\Http\Controllers\Admin\ProductController@autoComplateSellerCompany')->name('autocomplete.sellerCompany');

Route::get('/admin/autocomplete-sproduct','App\Http\Controllers\Admin\ProductController@autoComplateSProduct')->name('autocomplete.Sproduct');
//------------------Admin/Auto Complete Varients--
Route::get('/admin/autocomplete-product','App\Http\Controllers\Admin\ProductController@autoComplateProduct')->name('autocomplete.product');
//------------------Admin/Auto Complete Products-
Route::get('/admin/available_countries','App\Http\Controllers\Admin\ProductController@availbleCountries')->name('available.countries');

Route::post('/deleteimage','App\Http\Controllers\Admin\ProductController@deleteimage')->name('delete.productImage');

//------------------Admin/Auto Complete Categories-
Route::get('/offline_categories','App\Http\Controllers\FrontEnd\ProfileController@offlineCategories')->name('offline.categories');
Route::get('/company_types','App\Http\Controllers\FrontEnd\ProfileController@CompanyTypes')->name('company.types');
Route::get('/offline_categories_admin','App\Http\Controllers\FrontEnd\ProfileController@offlineCategoriesAdmin')->name('offline.categories.admin');
//------------------Admin/Product Brands Settings--
Route::get('/admin/brands','App\Http\Controllers\Admin\ProductBrandController@index')->name('admin.brands')->middleware('auth:admin');
Route::get('/brands/create','App\Http\Controllers\Admin\ProductBrandController@create')->name('brands.create')->middleware('auth:admin');
Route::post('/brands/create','App\Http\Controllers\Admin\ProductBrandController@store')->name('brands.create')->middleware('auth:admin');
Route::get('/brands/edit/{id}','App\Http\Controllers\Admin\ProductBrandController@edit')->name('brands.edit')->middleware('auth:admin');
Route::post('/brands/edit/{id}','App\Http\Controllers\Admin\ProductBrandController@update')->name('brands.update')->middleware('auth:admin');
Route::get('/brands/delete/{id}','App\Http\Controllers\Admin\ProductBrandController@destroy')->name('brands.destroy')->middleware('auth:admin');
Route::get('/brands/removeimage','App\Http\Controllers\Admin\ProductBrandController@remove_brandimage')->name('brands.removeImage')->middleware('auth:admin');
Route::get('getproductBrandlist','App\Http\Controllers\Admin\ProductBrandController@getproductBrandlist')->name('getproductBrandlist')->middleware('auth:admin');

//------------------Admin/Testimonials Settings--
Route::get('admin/testimonials', 'App\Http\Controllers\Admin\TestimonialsController@index')->name('admin.testimonials')->middleware('auth:admin');
Route::get('/testimonials/create', 'App\Http\Controllers\Admin\TestimonialsController@create')->name('testimonials.create')->middleware('auth:admin');
Route::post('/testimonials/create', 'App\Http\Controllers\Admin\TestimonialsController@store')->name('testimonials.create')->middleware('auth:admin');
Route::get('/testimonials/edit/{id}', 'App\Http\Controllers\Admin\TestimonialsController@edit')->name('testimonials.edit')->middleware('auth:admin');
Route::post('/testimonials/edit/{id}', 'App\Http\Controllers\Admin\TestimonialsController@update')->name('testimonials.edit')->middleware('auth:admin');
Route::get('/testimonials/delete/{id}', 'App\Http\Controllers\Admin\TestimonialsController@destroy')->name('testimonials.destroy')->middleware('auth:admin');
Route::post('/testimonials/removeimage', 'App\Http\Controllers\Admin\TestimonialsController@remove_testimonialimage')->name('testimonials.removeImage')->middleware('auth:admin');
Route::get('gettestimoniallist','App\Http\Controllers\Admin\TestimonialsController@gettestimoniallist')->name('gettestimoniallist')->middleware('auth:admin');

//------------------Admin/Social Media Settings--
Route::get('/admin/socialmedia', 'App\Http\Controllers\Admin\AdminController@socialmediaSetting')->name('admin.socialmedia')->middleware('auth:admin');
Route::post('/socialmedia/create', 'App\Http\Controllers\Admin\AdminController@socialmediaSettingCreate')->name('socialmedia.create')->middleware('auth:admin');
Route::post('/socialmedia/update', 'App\Http\Controllers\Admin\AdminController@socialmediaSettingUpdate')->name('socialmedia.update')->middleware('auth:admin');
Route::get('/socialmedia/delete/{id}', 'App\Http\Controllers\Admin\AdminController@socialmediadestroy')->name('socialmedia.destroy')->middleware('auth:admin');

//------------------Admin/Genral Settings--
Route::get('/admin/settings', 'App\Http\Controllers\Admin\AdminController@settings')->name('admin.settings')->middleware('auth:admin');
Route::post('/admin/settings', 'App\Http\Controllers\Admin\AdminController@storesettings')->name('admin.settings')->middleware('auth:admin');
Route::post('/admin/settings/removeimage', 'App\Http\Controllers\Admin\AdminController@remove_image')->name('admin.settings.removeImage')->middleware('auth:admin');

//------------------Admin/ContentPages/Slider--
Route::get('/sliders', 'App\Http\Controllers\Admin\SlidersController@index')->name('admin.sliders')->middleware('auth:admin');
Route::get('/sliders/create', 'App\Http\Controllers\Admin\SlidersController@create')->name('slider.create')->middleware('auth:admin');
Route::post('/sliders/create', 'App\Http\Controllers\Admin\SlidersController@store')->name('slider.insert')->middleware('auth:admin');
Route::get('/slider/edit/{id}', 'App\Http\Controllers\Admin\SlidersController@edit')->name('slider.edit')->middleware('auth:admin');
Route::post('/slider/edit/{id}', 'App\Http\Controllers\Admin\SlidersController@update')->name('slider.update')->middleware('auth:admin');
Route::get('/slider/view/{id}', 'App\Http\Controllers\Admin\SlidersController@show')->name('slider.show')->middleware('auth:admin');
Route::post('/slider/removeMedia', 'App\Http\Controllers\Admin\SlidersController@removeMedia')->name('slider.removeMedia')->middleware('auth:admin');
Route::get('/slider/delete/{id}', 'App\Http\Controllers\Admin\SlidersController@destroy')->name('slider.destroy')->middleware('auth:admin');

//------------------Admin/ContentPages/Mobile Slider--
Route::get('/mobile_sliders', 'App\Http\Controllers\Admin\MobileSlidersController@index')->name('admin.mobile_sliders')->middleware('auth:admin');
Route::get('/mobile_sliders/create', 'App\Http\Controllers\Admin\MobileSlidersController@create')->name('mobile_slider.create')->middleware('auth:admin');
Route::post('/mobile_sliders/create', 'App\Http\Controllers\Admin\MobileSlidersController@store')->name('mobile_slider.insert')->middleware('auth:admin');
Route::get('/mobile_slider/edit/{id}', 'App\Http\Controllers\Admin\MobileSlidersController@edit')->name('mobile_slider.edit')->middleware('auth:admin');
Route::post('/mobile_slider/edit/{id}', 'App\Http\Controllers\Admin\MobileSlidersController@update')->name('mobile_slider.update')->middleware('auth:admin');
Route::get('/mobile_slider/view/{id}', 'App\Http\Controllers\Admin\MobileSlidersController@show')->name('mobile_slider.show')->middleware('auth:admin');
Route::post('/mobile_slider/removeMedia', 'App\Http\Controllers\Admin\MobileSlidersController@removeMedia')->name('mobile_slider.removeMedia')->middleware('auth:admin');
Route::get('/mobile_slider/delete/{id}', 'App\Http\Controllers\Admin\MobileSlidersController@destroy')->name('mobile_slider.destroy')->middleware('auth:admin');

Route::post('updateactiveslider', 'App\Http\Controllers\Admin\SlidersController@updateactiveslider')->name('updateactiveslider')->middleware('auth:admin');
Route::post('mobile_updateactiveslider', 'App\Http\Controllers\Admin\MobileSlidersController@updateactiveslider')->name('mobile.updateactiveslider')->middleware('auth:admin');

Route::post('updatesellerfeaturedproduct', 'App\Http\Controllers\Admin\ProductController@updatesellerfeaturedproduct')->name('updatesellerfeaturedproduct')->middleware('auth:admin');
Route::post('updatesellerseasonproduct', 'App\Http\Controllers\Admin\ProductController@updatesellerseasonproduct')->name('updatesellerseasonproduct')->middleware('auth:admin');
Route::post('updateselerproductvisibility', 'App\Http\Controllers\Admin\ProductController@updateselerproductvisibility')->name('updateselerproductvisibility')->middleware('auth:admin');
//------------------Admin/ContentPages/Contentpages--
Route::get('/contentpages', 'App\Http\Controllers\Admin\ContentpagesController@index')->name('admin.contentpages')->middleware('auth:admin');
Route::get('/contentpages/create', 'App\Http\Controllers\Admin\ContentpagesController@create')->name('contentpages.create')->middleware('auth:admin');
Route::post('/contentpages/create', 'App\Http\Controllers\Admin\ContentpagesController@store')->name('contentpages.store')->middleware('auth:admin');
Route::get('/contentpages/view/{id}', 'App\Http\Controllers\Admin\ContentpagesController@show')->name('contentpages.show')->middleware('auth:admin');
Route::get('/contentpages/edit/{id}', 'App\Http\Controllers\Admin\ContentpagesController@edit')->name('contentpages.edit')->middleware('auth:admin');
Route::post('/contentpages/edit/{id}', 'App\Http\Controllers\Admin\ContentpagesController@update')->name('contentpages.update')->middleware('auth:admin');
Route::get('/contentpages/delete/{id}', 'App\Http\Controllers\Admin\ContentpagesController@destroy')->name('contentpages.destroy')->middleware('auth:admin');
Route::post('/contentpages/removeimage', 'App\Http\Controllers\Admin\ContentpagesController@remove_bannerimage')->name('contentpages.removeImage')->middleware('auth:admin');
Route::post('/content/ajaxtiny', 'App\Http\Controllers\Admin\ContentpagesController@ajaxtiny')->name('content.ajaxtiny')->middleware('auth:admin');

// sortable table example
 Route::get('/table', 'App\Http\Controllers\Admin\testimonialsController@table')->name('admin.table')->middleware('auth:admin');



//------------------Admin/Kyc Approvals
Route::get('/admin/kyc/list', 'App\Http\Controllers\Admin\AdminkycController@listuserkycdocs')->name('admin.kyclist')->middleware('auth:admin');
Route::get('getkyclist','App\Http\Controllers\Admin\AdminkycController@getkyclist')->name('getkyclist')->middleware('auth:admin');
Route::get('/admin/kyc-approve/{kycid}','App\Http\Controllers\Admin\AdminkycController@kycApprove')->name('admin.kyc.approve')->middleware('auth:admin');
Route::post('approveuserdocs','App\Http\Controllers\Admin\AdminkycController@approveuserdocs')->name('approveuserdocs')->middleware('auth:admin');

Route::post('rejectdocs','App\Http\Controllers\Admin\AdminkycController@rejectdocs')->name('rejectdocs')->middleware('auth:admin');
Route::get('/admin/products/edit-product/{productId}','App\Http\Controllers\Admin\ProductController@editProduct')->name('edit.product')->middleware('auth:admin');

Route::post('userkycupload','App\Http\Controllers\Admin\SellersController@userkycupload')->name('userkycupload')->middleware('auth:admin');
Route::get('availableusers','App\Http\Controllers\Admin\AdminkycController@availbleUsers')->name('available.users');

//------------------Admin/Sellers List
Route::get('/admin/sellers/list', 'App\Http\Controllers\Admin\SellersController@index')->name('admin.sellerslist')->middleware('auth:admin');
Route::get('getsellerslist','App\Http\Controllers\Admin\SellersController@getsellerslist')->name('getsellerslist')->middleware('auth:admin');
Route::get('/admin/seller/view/{sellerid}', 'App\Http\Controllers\Admin\SellersController@sellerdetails')->name('admin.sellerview')->middleware('auth:admin');

Route::get('/admin/seller/product/create/{sellerid}', 'App\Http\Controllers\Admin\SellersController@sellerproductcreate')->name('admin.sellerproduct_create')->middleware('auth:admin');

Route::post('/saveseller-product','App\Http\Controllers\Admin\SellersController@savesellerProduct')->name('save.seller_product')->middleware('auth:admin');

Route::get('/admin/seller/edit/{sellerid}', 'App\Http\Controllers\Admin\SellersController@selleredit')->name('admin.selleredit')->middleware('auth:admin');

Route::get('getsellerdocslist','App\Http\Controllers\Admin\SellersController@getsellerdocslist')->name('getsellerdocslist')->middleware('auth:admin');
Route::get('getsellerproductslist','App\Http\Controllers\Admin\SellersController@getsellerproductslist')->name('getsellerproductslist')->middleware('auth:admin');
Route::get('/admin/seller/product/view/{productId}','App\Http\Controllers\Admin\SellersController@viewProduct')->name('seller.view.product')->middleware('auth:admin');
Route::get('/admin/seller/product/edit/{productId}','App\Http\Controllers\Admin\SellersController@editProduct')->name('seller.edit.product')->middleware('auth:admin');
Route::post('/seller/update/product','App\Http\Controllers\Admin\SellersController@updateProduct')->name('seller.update.product')->middleware('auth:admin');
Route::get('/admin/seller/products/delete/{productId}','App\Http\Controllers\Admin\SellersController@deleteProduct')->name('seller.delete.product')->middleware('auth:admin');
Route::post('sellersstatusupdates','App\Http\Controllers\Admin\SellersController@sellersstatusupdates')->name('sellersstatusupdates')->middleware('auth:admin');
Route::get('/admin/seller/create', 'App\Http\Controllers\Admin\SellersController@create')->name('sellerprofile.create')->middleware('auth:admin');
Route::post('/admin/seller/store', 'App\Http\Controllers\Admin\SellersController@store')->name('sellerprofile.store')->middleware('auth:admin');
Route::post('adminresetpassword','App\Http\Controllers\Admin\SellersController@adminresetpassword')->name('adminresetpassword')->middleware('auth:admin');
Route::get('getpackagenamelist','App\Http\Controllers\Admin\SellersController@getpackagenamelist')->name('getpackagenamelist')->middleware('auth:admin');

Route::post('getpackageselected_details','App\Http\Controllers\Admin\SellersController@getpackageselected_details')->name('getpackageselected_details')->middleware('auth:admin');

Route::post('adminassignpackagetoadmin','App\Http\Controllers\Admin\SellersController@adminassignpackagetoadmin')->name('adminassignpackagetoadmin')->middleware('auth:admin');

//------------------Admin/Buyers List
Route::get('/admin/buyer/list', 'App\Http\Controllers\Admin\BuyersController@index')->name('admin.buyerslist')->middleware('auth:admin');
Route::get('getbuyerslist','App\Http\Controllers\Admin\BuyersController@getbuyerslist')->name('getsellerslist')->middleware('auth:admin');
Route::get('/admin/buyer/create', 'App\Http\Controllers\Admin\BuyersController@create')->name('buyerprofile.create')->middleware('auth:admin');
Route::post('/admin/buyer/store', 'App\Http\Controllers\Admin\BuyersController@store')->name('buyerprofile.store')->middleware('auth:admin');
Route::get('/admin/buyer/view/{sellerid}', 'App\Http\Controllers\Admin\BuyersController@buyerdetails')->name('admin.buyerview')->middleware('auth:admin');



//------------------Admin/Order List
Route::get('/admin/order/history', 'App\Http\Controllers\Admin\OrdersController@index')->name('admin.orderlist')->middleware('auth:admin');
Route::get('getorderslist','App\Http\Controllers\Admin\OrdersController@getorderslist')->name('getorderslist')->middleware('auth:admin');

//------------------Admin/Advertisement List
Route::get('/admin/advertisement/list', 'App\Http\Controllers\Admin\AdvertisementController@index')->name('admin.advertisementlist')->middleware('auth:admin');
Route::get('getadvertisementlist','App\Http\Controllers\Admin\AdvertisementController@getadvertisementlist')->name('getadvertisementslist')->middleware('auth:admin');
Route::get('/admin/advertisement/create', 'App\Http\Controllers\Admin\AdvertisementController@create')->name('advertisement.create')->middleware('auth:admin');
Route::post('/admin/advertisement/store', 'App\Http\Controllers\Admin\AdvertisementController@store')->name('advertisement.store')->middleware('auth:admin');
Route::get('/admin/advertisement/edit/{id}', 'App\Http\Controllers\Admin\AdvertisementController@edit')->name('advertisement.edit')->middleware('auth:admin');
Route::post('/admin/advertisement/update', 'App\Http\Controllers\Admin\AdvertisementController@update')->name('advertisement.update')->middleware('auth:admin');
Route::get('/admin/advertisement/delete/{id}', 'App\Http\Controllers\Admin\AdvertisementController@deleteadv')->name('advertisement.destroy')->middleware('auth:admin');

Route::get('pagepositionavailable', 'App\Http\Controllers\Admin\AdvertisementController@pagepositionavailable')->name('pagepositionavailable')->middleware('auth:admin');


//------------------Admin/Vendor Product List
Route::get('/admin/vendor/products', 'App\Http\Controllers\Admin\VendorProductController@listVendorProduct')->name('admin.listVendorProduct')->middleware('auth:admin');
Route::get('getvendorproductlist','App\Http\Controllers\Admin\VendorProductController@getvendorproductlist')->name('getvendorproductlist')->middleware('auth:admin');

Route::post('vendorproductapproval','App\Http\Controllers\Admin\VendorProductController@vendorproductapproval')->name('vendorproductapproval')->middleware('auth:admin');

Route::post('vendorproductdelete','App\Http\Controllers\Admin\VendorProductController@vendorproductdelete')->name('vendorproductdelete')->middleware('auth:admin');


Route::get('/admin/vendor/products/view/{id}', 'App\Http\Controllers\Admin\VendorProductController@viewProduct')->name('vendor_product.view')->middleware('auth:admin');

Route::get('/admin/vendor/products/edit/{id}', 'App\Http\Controllers\Admin\VendorProductController@editProduct')->name('vendor_product.edit')->middleware('auth:admin');

Route::delete('/deletevendorimage/{id}','App\Http\Controllers\Admin\VendorProductController@deleteimage')->name('delete.vendorimage');

Route::post('/update-vendor-product','App\Http\Controllers\Admin\VendorProductController@updateProduct')->name('update.vendorproduct')->middleware('auth:admin');

Route::get('/admin/vendor/products/delete/{productId}','App\Http\Controllers\Admin\VendorProductController@deleteProduct')->name('delete.vendorproduct')->middleware('auth:admin');

Route::get('getuserssubscriptionlist', 'App\Http\Controllers\Admin\PackageController@getuserssubscriptionlist')->name('getuserssubscriptionlist')->middleware('auth:admin');

//------------------Admin/ Front End Menu Settings--

Route::get('/admin/settings/frontendmenu', 'App\Http\Controllers\Admin\AdminController@frontendmenulist')->name('admin.listfrontendmenu')->middleware('auth:admin');
Route::post('/admin/settings/frontendmenu','App\Http\Controllers\Admin\AdminController@saveFrontEndMenu')->name('admin.savefrontendmenu')->middleware('auth:admin');

Route::post('/admin/searchcriteria', 'App\Http\Controllers\Admin\AdminController@storesearchcriteria')->name('admin.storesearchcriteria')->middleware('auth:admin');

Route::get('/admin/newsletters/list', 'App\Http\Controllers\Admin\SellersController@NewsLettersLists')->name('admin.newsletters')->middleware('auth:admin');
Route::get('getnewsletterslist','App\Http\Controllers\Admin\SellersController@getnewsletterslist')->name('getnewsletterslist')->middleware('auth:admin');

Route::get('/admin/promotionalnewsletters/list', 'App\Http\Controllers\Admin\SellersController@promotionalNewsLettersLists')->name('admin.promotionalnewsletters')->middleware('auth:admin');

Route::get('getpromotionalnewsletterslist','App\Http\Controllers\Admin\SellersController@getpromotionalnewsletterslist')->name('getpromotionalnewsletterslist')->middleware('auth:admin');

//---------------------- seller/buyer update
Route::post('/admin/seller/update', 'App\Http\Controllers\Admin\SellersController@update')->name('sellerprofile.update')->middleware('auth:admin');
Route::get('/admin/users/delete/{userid}', 'App\Http\Controllers\Admin\SellersController@deleteuser')->name('delete.selleruser')->middleware('auth:admin');
Route::get('/admin/buyer/edit/{sellerid}', 'App\Http\Controllers\Admin\BuyersController@buyeredit')->name('admin.buyeredit')->middleware('auth:admin');

Route::post('/admin/buyer/update', 'App\Http\Controllers\Admin\BuyersController@update')->name('buyerprofile.update')->middleware('auth:admin');

Route::get('getcosellerslist','App\Http\Controllers\Admin\SellersController@getcosellerslist')->name('getcosellerslist')->middleware('auth:admin');

Route::get('profile_account_delete', 'App\Http\Controllers\FrontEnd\SellerController@profile_account_delete')->name('profile_account_delete')->middleware('auth:user');
Route::get('user_status', 'App\Http\Controllers\FrontEnd\SellerController@user_status')->name('user_status');
Route::get('getrequestdeletelist','App\Http\Controllers\Admin\RequestDeleteController@getrequestdeletelist')->name('getrequestdeletelist')->middleware('auth:admin');


Route::post('adminprofiledelete','App\Http\Controllers\Admin\RequestDeleteController@adminprofiledelete')->name('adminprofiledelete')->middleware('auth:admin');

Route::get('/admin/profile/request/delete', 'App\Http\Controllers\Admin\RequestDeleteController@index')->name('admin.profilerequest')->middleware('auth:admin');

Route::post('adminusersellersstatusupdates','App\Http\Controllers\Admin\SellersController@adminusersellersstatusupdates')->name('adminusersellersstatusupdates')->middleware('auth:admin');
Route::get('profile_account_delete', 'App\Http\Controllers\FrontEnd\SellerController@profile_account_delete')->name('profile_account_delete')->middleware('auth:user');
Route::get('getrequestdeletelist','App\Http\Controllers\Admin\RequestDeleteController@getrequestdeletelist')->name('getrequestdeletelist')->middleware('auth:admin');
Route::post('adminprofiledelete','App\Http\Controllers\Admin\RequestDeleteController@adminprofiledelete')->name('adminprofiledelete')->middleware('auth:admin');
Route::get('/admin/profile/request/delete', 'App\Http\Controllers\Admin\RequestDeleteController@index')->name('admin.profilerequest')->middleware('auth:admin');

Route::get('/admin/stripe-status', 'App\Http\Controllers\Admin\AdminController@stripeStatus')->name('stripe.status')->middleware('auth:admin');
Route::get('stripe_status', 'App\Http\Controllers\Admin\AdminController@stripe_status')->name('stripe_status');
Route::post('currencymerge','App\Http\Controllers\Admin\CurrencyController@currencymerge')->name('currencymerge')->middleware('auth:admin');
