import { Either, left, right } from "@sweet-monads/either";
import { Observable, of, Subject } from "rxjs";
import { Validators } from "../src/modules/user/uses-cases/registration/validators";

test('Registration.Validator; Success validation data', () => {
  const either = Validators.valid({
    name: 'name',
    email: 'email@gmail.com',
    password: '12345',
    confirmPassword: '12345'
  });

  expect(either.isRight()).toBe(true);
});

// test('Registration.Validator; Invalid email', () => {
//   const validator = new Registration.Validator();

//   const maybeRight = validator.valid({
//     name: "Test",
//     email: "Invalid email",
//     password: "1qw21w!",
//     confirmPassword: "1qw21w!"
//   });

//   expect(maybeRight.isLeft()).toBe(true);
// });

// test('Registration.Validator; Invalid name', () => {
//   const validator = new Registration.Validator();

//   const maybeRight = validator.valid({
//     name: "Test1",
//     email: "test@gmail.com",
//     password: "1qw21w!",
//     confirmPassword: "1qw21w!"
//   });

//   expect(maybeRight.isLeft()).toBe(true);
// });

// test('Registration.Validator; Passwords are not equal', () => {
//   const validator = new Registration.Validator();

//   const maybeRight = validator.valid({
//     name: "Test",
//     email: "test@gmail.com",
//     password: "1qw21w!",
//     confirmPassword: "1qw21w!1"
//   });

//   expect(maybeRight.isLeft()).toBe(true);
// });

// const successApi: Registration.Ports.Api = {
//   fetch: (dto: Registration.Dto): Observable<Either<Error[], Registration.Success>> => {
//     if (dto.email == 'name@gmail.com') {
//       return of(left<Error[], Registration.Success>([
//         new Infrastructure.BadRequest('User already exists') as Error
//       ]));
//     }
//     return of(right(new Registration.Success('Success')));
//   }
// }

// test('new Registration.UseCase', () => {
//   const subject = new Subject<Either<Error[], Registration.Success>>();

//   const emiter: Registration.Ports.Emiter = {
//     emit: (either: Either<Error[], Registration.Success>): void => {
//       subject.next(either);
//     }
//   }

//   const useCase = new Registration.UseCase(
//     successApi,
//     emiter
//   );
//   expect(useCase instanceof Registration.UseCase).toBe(true);
// });

// test('Registration.UseCase; Success registration', () => {
//   const subject = new Subject<Either<Error[], Registration.Success>>();

//   const emiter: Registration.Ports.Emiter = {
//     emit: (either: Either<Error[], Registration.Success>): void => {
//       subject.next(either);
//     }
//   }

//   const useCase = new Registration.UseCase(
//     successApi,
//     emiter
//   );

//   subject.subscribe({
//     next: (e: Either<Error[], Registration.Success>) => {
//       expect(e.value instanceof Registration.Success).toBe(true);
//       subject.unsubscribe();
//     },
//   });

//   useCase.registry({
//     name: "Test",
//     email: "test@gmail.com",
//     password: "1qw21w!",
//     confirmPassword: "1qw21w!"
//   });
// });

// test('Registration.UseCase; Invalid email', () => {
//   const subject = new Subject<Either<Error[], Registration.Success>>();

//   const emiter: Registration.Ports.Emiter = {
//     emit: (either: Either<Error[], Registration.Success>): void => {
//       subject.next(either);
//     }
//   }

//   const useCase = new Registration.UseCase(
//     successApi,
//     emiter
//   );

//   subject.subscribe({
//     next: (e: Either<Error[], Registration.Success>) => {
//       const err = (e.value as Error[]).pop() as Error;
//       expect(err instanceof Domain.InvalidEmail).toBe(true);
//       subject.unsubscribe();
//     },
//   });

//   useCase.registry({
//     name: "Test",
//     email: "test@!",
//     password: "1qw21w!",
//     confirmPassword: "1qw21w!"
//   });
// });

// test('Registration.UseCase; Invalid name', () => {
//   const subject = new Subject<Either<Error[], Registration.Success>>();

//   const emiter: Registration.Ports.Emiter = {
//     emit: (either: Either<Error[], Registration.Success>): void => {
//       subject.next(either);
//     }
//   }

//   const useCase = new Registration.UseCase(
//     successApi,
//     emiter
//   );

//   subject.subscribe({
//     next: (e: Either<Error[], Registration.Success>) => {
//       const err = (e.value as Error[]).pop() as Error;
//       expect(err instanceof Domain.InvalidName).toBe(true);
//       subject.unsubscribe();
//     },
//   });

//   useCase.registry({
//     name: "Test1",
//     email: "test@gmail.com",
//     password: "1qw21w!",
//     confirmPassword: "1qw21w!"
//   });
// });

// test('Registration.UseCase; Invalid password', () => {
//   const subject = new Subject<Either<Error[], Registration.Success>>();

//   const emiter: Registration.Ports.Emiter = {
//     emit: (either: Either<Error[], Registration.Success>): void => {
//       subject.next(either);
//     }
//   }

//   const useCase = new Registration.UseCase(
//     successApi,
//     emiter
//   );

//   subject.subscribe({
//     next: (e: Either<Error[], Registration.Success>) => {
//       const err = (e.value as Error[]).pop() as Error;
//       expect(err instanceof Domain.InvalidPassword).toBe(true);
//       subject.unsubscribe();
//     },
//   });

//   useCase.registry({
//     name: "Test",
//     email: "test@gmail.com",
//     password: "Инструменты",
//     confirmPassword: "Инструменты"
//   });
// });

// test('Registration.UseCase; Password not equal', () => {
//   const subject = new Subject<Either<Error[], Registration.Success>>();

//   const emiter: Registration.Ports.Emiter = {
//     emit: (either: Either<Error[], Registration.Success>): void => {
//       subject.next(either);
//     }
//   }

//   const useCase = new Registration.UseCase(
//     successApi,
//     emiter
//   );

//   subject.subscribe({
//     next: (e: Either<Error[], Registration.Success>) => {
//       const err = (e.value as Error[]).pop() as Error;
//       expect(err instanceof Registration.Errors.PasswordNotEqual).toBe(true);
//       subject.unsubscribe();
//     },
//   });

//   useCase.registry({
//     name: "Test",
//     email: "test@gmail.com",
//     password: "1qw21w!",
//     confirmPassword: "1q"
//   });
// });

// test('Registration.UseCase; Bad request', () => {
//   const subject = new Subject<Either<Error[], Registration.Success>>();

//   const emiter: Registration.Ports.Emiter = {
//     emit: (either: Either<Error[], Registration.Success>): void => {
//       subject.next(either);
//     }
//   }

//   const useCase = new Registration.UseCase(
//     successApi,
//     emiter
//   );

//   subject.subscribe({
//     next: (e: Either<Error[], Registration.Success>) => {
//       const err = (e.value as Error[]).pop() as Error;
//       expect(err instanceof Infrastructure.BadRequest).toBe(true);
//       subject.unsubscribe();
//     },
//   });

//   useCase.registry({
//     name: "Test",
//     email: "name@gmail.com",
//     password: "1qw21w!",
//     confirmPassword: "1qw21w!"
//   });
// });

export {}
