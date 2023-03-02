import { Registration } from '../src/use-cases/registration';

test('new Registration.Validator', () => {
  const validator = new Registration.Validator();
  expect(validator instanceof Registration.Validator).toBe(true);
});

test('Validation data', () => {
  const validator = new Registration.Validator();

  const maybeRight = validator.valid({
    name: "Test",
    email: "test@gmail.com",
    password: "1qw21w!",
    confirmPassword: "1qw21w!"
  });

  expect(maybeRight.isRight()).toBe(true);
});

test('Invalid email', () => {
  const validator = new Registration.Validator();

  const maybeRight = validator.valid({
    name: "Test",
    email: "Invalid email",
    password: "1qw21w!",
    confirmPassword: "1qw21w!"
  });

  expect(maybeRight.isLeft()).toBe(true);
});

export {}
