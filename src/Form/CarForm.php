<?php
/**
 * Created by PhpStorm.
 * User: Armando
 * Date: 18/09/2018
 * Time: 11:21
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class CarForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',TextType::class,['label' => 'Nombre del Chofer'])
            ->add('tel', TelType::class, ['label' => 'Teléfono fijo'])
            ->add('cel', TelType::class, ['label' => 'Teléfono móvil'])
            ->add('chapa', TextType::class, ['label' => 'Chapa'])
            ->add('modelo', TextType::class, ['label' => 'Modelo y Año'])
            ->add('color', TextType::class, ['label' => 'Color'])
            ->add('licencia', ChoiceType::class, ['label' => 'Tipo de Licencia','choices'  => array(
                'Asociado' => 'Asociado',
                'Licencia operativa' => 'Licencia operativa',
            ),])
            ->add('provincia', ChoiceType::class, ['label' => 'Provincia','choices'  => array(
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
            ->add('climatizado', ChoiceType::class, ['label' => 'Climatizado','choices'  => array(
                'Si' => true,
                'No' => false,
            ),])
            ->add('descapotable', ChoiceType::class, ['label' => 'Descapotable','choices'  => array(
                'Si' => true,
                'No' => false,
            ),])
            ->add('cantidadAsientos',null, ['label' => 'Cantidad de Asientos'])
            ->add('idiomas',null, ['label' => 'Idiomas'])
            ->add('active', ChoiceType::class, ['label' => 'Activo','choices'  => array(
                'Si' => true,
                'No' => false,
            ),])
            ->add('Guardar', SubmitType::class);
    }
}