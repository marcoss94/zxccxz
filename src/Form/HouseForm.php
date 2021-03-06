<?php
/**
 * Created by PhpStorm.
 * User: Armando
 * Date: 18/09/2018
 * Time: 18:49
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HouseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo',TextType::class,['label' => 'Código'])
            ->add('nombre', TextType::class, ['label' => 'Nombre'])
            ->add('name', TextType::class, ['label' => 'Name'])
            ->add('manager', TextType::class, ['label' => 'Manager'])
            ->add('descripcion', TextareaType::class, ['label' => 'Descripcion','required'=> false])
            ->add('description', TextareaType::class, ['label' => 'Description','required'=> false])
            ->add('direccion', TextType::class, ['label' => 'Dirección'])
            ->add('municipio', TextType::class, ['label' => 'Municipio'])
            ->add('provincia', ChoiceType::class, ['label' => 'Provincia', 'choices' => array(
                'Pinar del Rio' => 'Pinar del Rio',
                'Artemisa' => 'Artemisa',
                'La Habana' => 'La Habana',
                'Mayabeque' => 'Mayabeque',
                'Matanzas' => 'Matanzas',
                'Villa Clara' => 'Villa Clara',
                'Cienfuegos' => 'Cienfuegos',
                'Sancti Spiritus' => 'Sancti Spiritus',
                'Ciego de Ávila' => 'Ciego de Ávila',
                'Camaguey' => 'Camaguey',
                'Las Tunas' => 'Las Tunas',
                'Holguín' => 'Holguín',
                'Granma' => 'Granma',
                'Santiago de Cuba' => 'Santiago de Cuba',
                'Guantánamo' => 'Guantánamo',
                'Isla de la Juventud' => 'Isla de la Juventud',
            ),])
            ->add('tel', TelType::class, ['label' => 'Teléfono fijo','required'=> false])
            ->add('email', EmailType::class, ['label' => 'Email','required'=> false])
            ->add('cel', TelType::class, ['label' => 'Teléfono móvil','required'=> false])
            ->add('licencia', TextType::class, ['label' => '#Licencia','required'=> false])
            ->add('tipoEstablecimiento', ChoiceType::class, ['label' => 'Tipo de Establecimiento', 'choices' => array(
                'casa' => 'casa',
                'habitación' => 'habitación',
                'apartamento' => 'apartamento',
            ),])
            ->add('composicion', ChoiceType::class, ['label' => 'Composición', 'choices' => array(
                'Cocina' => 'cocina',
                'Terraza' => 'terraza',
                'Patio' => 'patio',
                'Jacuzzi' => 'jacuzzi',
                'Caja Fuerte' => 'caja_fuerte',
                'Hamaca' => 'hamaca',
                'Escritorio de Trabajo' => 'escritorio',
                'Ducha de Playa' => 'ducha_playa',
                'Piscina' => 'piscina',
                'Cámaras de seguridad' => 'camaras',
                'Cunas para bebes' => 'cuna',
                'Bar/Restaurant' => 'bar_Restaurant',
                'Equipos de música' => 'equipo_música',
                'Parqueo' => 'parqueo',
            ), 'expanded' => true, 'multiple' => true])
            ->add('servicios', ChoiceType::class, ['label' => 'Servicios', 'choices' => array(
                'Desayuno' => 'desayuno',
                'Almuerzo' => 'almuerzo',
                'Cena' => 'cena',
                'Lavandería' => 'lavandería',
                'Masaje' => 'masaje',
                'Bar/Restaurant' => 'bar_Restaurant',
                'Limpieza' => 'limpieza',
                'Peluquería'=> 'peluqueria'
            ), 'expanded' => true, 'multiple' => true])
            ->add('latitud', NumberType::class, ['label' => 'Latitud'])
            ->add('longitud', NumberType::class, ['label' => 'Longitud'])
            ->add('active', ChoiceType::class, ['label' => 'Activo', 'choices' => array(
                'si' => true,
                'no' => false,
            ),])
            ->add('valoracion', ChoiceType::class, ['label' => 'Valoración', 'choices' => array(
                '0' => 0,
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
                '6' => 6,
                '7' => 7,
                '8' => 8,
                '9' => 9,
                '10' => 10,
            ),])
            ->add('Guardar', SubmitType::class);
    }

}