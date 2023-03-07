import { Either, right } from "@sweet-monads/either";
import { of, Subject } from "rxjs";
import { RegistrationUseCase } from "../src/use-cases/registration";
import { RegistrationAdapters } from '../src/adapters/registration';

const api: RegistrationUseCase.Ports.Api = {
  fetch: () => {
    return of(right(true))
  }
};

test('RegistrationUseCase.Validators; Success validation data', () => {
  const either = RegistrationUseCase.Validators.valid({
    name: 'name',
    email: 'email@gmail.com',
    password: '12345',
    confirmingPassword: '12345'
  })

  expect(either.isRight()).toBe(true)
})

test('RegistrationUseCase.Validators; Invalid email', () => {
  const either = RegistrationUseCase.Validators.valid({
    name: 'name',
    email: '',
    password: '12345',
    confirmingPassword: '12345'
  })

  expect(either.isLeft()).toBe(true)
})

test('RegistrationUseCase.Validators; Invalid name', () => {
  const either = RegistrationUseCase.Validators.valid({
    name: '',
    email: 'email@gmail.com',
    password: '12345',
    confirmingPassword: '12345'
  })

  expect(either.isLeft()).toBe(true)
})

test('RegistrationUseCase.Validators; Passwords are not equal', () => {
  const either = RegistrationUseCase.Validators.valid({
    name: 'name',
    email: 'email@gmail.com',
    password: '12345',
    confirmingPassword: '123457'
  })

  expect(either.isLeft()).toBe(true)
})

test('RegistrationUseCase.Validators; Invalid passwords', () => {
  const either = RegistrationUseCase.Validators.valid({
    name: 'name',
    email: 'email@gmail.com',
    password: '',
    confirmingPassword: '123457'
  })

  expect(either.isLeft()).toBe(true)
})

test('RegistrationUseCase.UseCase; Success registration', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new RegistrationAdapters.Emiter(subject)

  const useCase = new RegistrationUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isRight()).toBe(true);
      subject.unsubscribe();
    },
  })

  useCase.registry({
    name: 'name',
    email: 'email@gmail.com',
    password: '12345',
    confirmingPassword: '12345'
  })
})

test('RegistrationUseCase.UseCase; Invalid email', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new RegistrationAdapters.Emiter(subject)

  const useCase = new RegistrationUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isLeft()).toBe(true)
      subject.unsubscribe()
    },
  })

  useCase.registry({
    name: 'name',
    email: '',
    password: '12345',
    confirmingPassword: '12345'
  })
})

test('RegistrationUseCase.UseCase; Invalid name', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new RegistrationAdapters.Emiter(subject)

  const useCase = new RegistrationUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isLeft()).toBe(true)
      subject.unsubscribe()
    },
  })

  useCase.registry({
    name: '',
    email: 'email@gmail.com',
    password: '12345',
    confirmingPassword: '12345'
  })
})

test('RegistrationUseCase.UseCase; Invalid password', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new RegistrationAdapters.Emiter(subject)

  const useCase = new RegistrationUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isLeft()).toBe(true)
      subject.unsubscribe()
    },
  })

  useCase.registry({
    name: 'name',
    email: 'email@gmail.com',
    password: '',
    confirmingPassword: '12345'
  })
})

test('RegistrationUseCase.UseCase; Password are not equal', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new RegistrationAdapters.Emiter(subject)

  const useCase = new RegistrationUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isLeft()).toBe(true)
      subject.unsubscribe()
    },
  })

  useCase.registry({
    name: 'name',
    email: 'email@gmail.com',
    password: '12345',
    confirmingPassword: '1'
  })
})

export {}
