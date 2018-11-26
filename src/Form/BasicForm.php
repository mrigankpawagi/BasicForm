<?php

namespace Drupal\basic_form\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Module to display a basic form page in Drupal 8.
 */
class BasicForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'basic_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['name'] = [
      '#type' => 'textfield',
      '#title' => 'Your Full Name',
      '#id' => 'name',
      '#attributes' => [
        'placeholder' => 'Shorter than 50 characters',
      ],
    ];
    $form['birthdate'] = [
      '#type' => 'date',
      '#title' => 'Date of Birth',
      '#id' => 'birthdate',
    ];
    $form['gender'] = [
      '#type' => 'radios',
      '#title' => 'Gender',
      '#id' => 'gender',
      '#options' => [
        0 => $this->t('Male'),
        1 => $this->t('Female'),
      ],
      '#default_value' => 0,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#id' => 'submit',
    ];

    $form['#attached']['library'][] = 'basic_form/basicForm';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (strlen($form_state->getValue('name')) == 0) {
      $form_state->setErrorByName('name', $this->t("'Name' Field is Mandatory."));
    }
    if (strlen($form_state->getValue('name')) > 50) {
      $form_state->setErrorByName('name', $this->t('Name cannot be longer than 50 characters.'));
    }
    if (strlen($form_state->getValue('birthdate')) == 0) {
      $form_state->setErrorByName('birthdate', $this->t("'Date of Birth' Field is Mandatory."));
    }
    $dob = strtotime($form_state->getValue('birthdate'));
    if (($dob + 567648000) > time()) {
      // (567648000 seconds = 18 years)
      $form_state->setErrorByName('birthdate', $this->t('You must be older than 18 years of age.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message(
      $this->t('Thank You @name!<br>Birthdate: @birthdate<br>Gender: @gender',
        [
          '@name' => $form_state->getValue('name'),
          '@birthdate' => $form_state->getValue('birthdate'),
          '@gender' => ['Male', 'Female'][$form_state->getValue('gender')],
        ]
      )
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'basic_form.settings',
    ];
  }

}
