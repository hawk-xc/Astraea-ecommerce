<?php

namespace App\Providers;

use App\Interfaces\UserOrderInterface;
use App\Interfaces\CategoriesInterface;
use App\Interfaces\SubCategoriesInterface;
use App\Interfaces\ProductInterface;
use App\Interfaces\ImageProductInterface;
use App\Interfaces\HampersProductInterface;
use App\Interfaces\HampersImageProductInterface;
use App\Interfaces\PaymentMethodInterface;
use App\Interfaces\PaymentDetailInterface;
use App\Interfaces\UsersInterface;
use App\Interfaces\AboutUsInterface;
use App\Interfaces\ContactUsInterface;
use App\Interfaces\ServiceInterface;
use App\Interfaces\PartnerInterface;
use App\Interfaces\CertificateInterface;
use App\Interfaces\EventInterface;
use App\Interfaces\DistrictInterface;
use App\Interfaces\DiscountInterface;
use App\Interfaces\DiscountProductInterface;
use App\Interfaces\DiscountHampersInterface;
use App\Interfaces\DiscountUserInterface;
use App\Interfaces\DiscountCostumerInterface;
use App\Interfaces\CustomerInterface;
use App\Interfaces\OrderInterface;
use App\Interfaces\OrderDetailInterface;
use App\Interfaces\OrderHampersInterface;
use App\Interfaces\OrderHampersDetailInterface;
use App\Interfaces\AppFeeInterface;
use App\Interfaces\ColorInterface;
use App\Interfaces\TestimoniInterface;
use App\Interfaces\UlasaniInterface;
use App\Interfaces\VisitorMailInterface;
use App\Repositories\UserOrderRepository;
use App\Repositories\CategoriesRepository;
use App\Repositories\SubCategoriesRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ImageProductRepository;
use App\Repositories\HampersProductRepository;
use App\Repositories\HampersImageProductRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\PaymentDetailRepository;
use App\Repositories\UsersRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\ContactUsRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\PartnerRepository;
use App\Repositories\CertificateRepository;
use App\Repositories\EventRepository;
use App\Repositories\DistrictRepository;
use App\Repositories\DiscountRepository;
use App\Repositories\DiscountProductRepository;
use App\Repositories\DiscountHampersRepository;
use App\Repositories\DiscountUserRepository;
use App\Repositories\DiscountCostumerRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderHampersRepository;
use App\Repositories\OrderHampersDetailRepository;
use App\Repositories\AppFeeRepository;
use App\Repositories\ColorRepository;
use App\Repositories\TestimoniRepository;
use App\Repositories\UlasanRepository;
use App\Repositories\VisitorMailRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(CategoriesInterface::class, CategoriesRepository::class);
        $this->app->bind(CategoriesInterface::class, CategoriesRepository::class);
        $this->app->bind(SubCategoriesInterface::class, SubCategoriesRepository::class);
        $this->app->bind(ProfilesInterface::class, ProfilesRepository::class);
        $this->app->bind(UsersInterface::class, UsersRepository::class);
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(ImageProductInterface::class, ImageProductRepository::class);
        $this->app->bind(HampersProductInterface::class, HampersProductRepository::class);
        $this->app->bind(HampersImageProductInterface::class, HampersImageProductRepository::class);
        $this->app->bind(PaymentMethodInterface::class, PaymentMethodRepository::class);
        $this->app->bind(PaymentDetailInterface::class, PaymentDetailRepository::class);
        $this->app->bind(AboutUsInterface::class, AboutUsRepository::class);
        $this->app->bind(ContactUsInterface::class, ContactUsRepository::class);
        $this->app->bind(ServiceInterface::class, ServiceRepository::class);
        $this->app->bind(PartnerInterface::class, PartnerRepository::class);
        $this->app->bind(CertificateInterface::class, CertificateRepository::class);
        $this->app->bind(EventInterface::class, EventRepository::class);
        $this->app->bind(DistrictInterface::class, DistrictRepository::class);
        $this->app->bind(DiscountInterface::class, DiscountRepository::class);
        $this->app->bind(DiscountProductInterface::class, DiscountProductRepository::class);
        $this->app->bind(DiscountHampersInterface::class, DiscountHampersRepository::class);
        $this->app->bind(DiscountUserInterface::class, DiscountUserRepository::class);
        $this->app->bind(DiscountCustomerInterface::class, DiscountCustomerRepository::class);
        $this->app->bind(CustomerInterface::class, CustomerRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
        $this->app->bind(OrderDetailInterface::class, OrderDetailRepository::class);
        $this->app->bind(OrderHampersInterface::class, OrderHampersRepository::class);
        $this->app->bind(OrderHampersDetailInterface::class, OrderHampersDetailRepository::class);
        $this->app->bind(AppFeeInterface::class, AppFeeRepository::class);
        $this->app->bind(ColorInterface::class, ColorRepository::class);
        $this->app->bind(UserOrderInterface::class, UserOrderRepository::class);
        $this->app->bind(TestimoniInterface::class, TestimoniRepository::class);
        $this->app->bind(UlasanInterface::class, UlasanRepository::class);
        $this->app->bind(VisitorMailInterface::class, VisitorMailRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
