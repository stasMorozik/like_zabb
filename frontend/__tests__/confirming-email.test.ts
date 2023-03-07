import { Either, right } from "@sweet-monads/either";
import { of, Subject } from "rxjs";
import { ConfirmingEmailUseCase } from "../src/use-cases/confirming-email";
import { ConfirmingEmailAdapters } from '../src/adapters/confirming-email';

const api: ConfirmingEmailUseCase.Ports.Api = {
  fetch: () => {
    return of(right(true))
  }
}

test('ConfirmingEmailUseCase.Validators; Success validation data', () => {
  const either = ConfirmingEmailUseCase.Validators.valid({
    email: 'email@gmail.com',
    code: 1234
  })

  expect(either.isRight()).toBe(true);
})

test('ConfirmingEmailUseCase.Validators; Invalid email', () => {
  const either = ConfirmingEmailUseCase.Validators.valid({
    email: '',
    code: 1234
  })

  expect(either.isLeft()).toBe(true)
})

test('ConfirmingEmailUseCase.Validators; Invalid code', () => {
  const either = ConfirmingEmailUseCase.Validators.valid({
    email: 'email@gmail.com',
    code: 0
  })

  expect(either.isLeft()).toBe(true)
})

test('ConfirmingEmailUseCase.UseCase; Success confirmation', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new ConfirmingEmailAdapters.Emiter(subject)

  const useCase = new ConfirmingEmailUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isRight()).toBe(true)
      subject.unsubscribe()
    },
  })

  useCase.confirm({
    email: 'email@gmail.com',
    code: 1234
  })
})

export {}
