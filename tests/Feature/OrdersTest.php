<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrdersTest extends TestCase
{
    /**
     * @test
     */
    public function it_loads_the_orders_list()
    {
        $response = $this->get("/orders");

        $response->assertStatus(200)
            ->assertSee("Lista de ordenes")
            ->assertSee("ID")
            ->assertSee("Fecha")
            ->assertSee("Opciones");
    }

    /**
     * @test
     */
    public function it_loads_the_create_order()
    {
        $response = $this->get("/orders/create");

        $response->assertStatus(200)
            ->assertSee("Nombre y apellido")
            ->assertSee("E-mail")
            ->assertSee("Numero de celular");
    }

    /**
     * @test
     */
    public function it_creates_a_new_order()
    {
        $response = $this->followingRedirects()
            ->post(route('orders.store'), [
                "customer_name" => "Jhon Doe",
                "customer_email" => "jhon.doe@example.com",
                "customer_mobile" => "123123123"
            ]);
            
        $response->assertStatus(200)
            ->assertSee("Jhon Doe")
            ->assertSee("jhon.doe@example.com")
            ->assertSee("123123123")
            ->assertSee("Pago no iniciado");
    }
}
