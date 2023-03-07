import { Either, right } from "@sweet-monads/either";
import { of, Subject } from "rxjs";
import { AuthenticationUseCase } from "../src/use-cases/authentication";
import { AuthenticationAdapters } from '../src/adapters/authentication';

const api: AuthenticationUseCase.Ports.Api = {
  fetch: () => {
    return of(right(true))
  }
};

test('AuthenticationUseCase.Validators; Success validation data', () => {
  const either = AuthenticationUseCase.Validators.valid({
    email: 'email@gmail.com',
    password: '12345'
  })

  expect(either.isRight()).toBe(true);
})

test('AuthenticationUseCase.Validators; Invalid email', () => {
  const either = AuthenticationUseCase.Validators.valid({
    email: '',
    password: '12345'
  })

  expect(either.isLeft()).toBe(true);
})

test('AuthenticationUseCase.Validators; Invalid password', () => {
  const either = AuthenticationUseCase.Validators.valid({
    email: 'email@gmail.com',
    password: ''
  })

  expect(either.isLeft()).toBe(true);
})

test('AuthenticationUseCase.UseCase; Success authentication', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new AuthenticationAdapters.Emiter(subject)

  const useCase = new AuthenticationUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isRight()).toBe(true);
      subject.unsubscribe();
    },
  })

  useCase.auth({
    email: 'email@gmail.com',
    password: '12345'
  })
})

test('AuthenticationUseCase.UseCase; Invalid email', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new AuthenticationAdapters.Emiter(subject)

  const useCase = new AuthenticationUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isLeft()).toBe(true);
      subject.unsubscribe();
    },
  })

  useCase.auth({
    email: '',
    password: '12345'
  })
})

test('AuthenticationUseCase.UseCase; Invalid password', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new AuthenticationAdapters.Emiter(subject)

  const useCase = new AuthenticationUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isLeft()).toBe(true);
      subject.unsubscribe();
    },
  })

  useCase.auth({
    email: 'email@gmail.com',
    password: ''
  })
})

export {}
