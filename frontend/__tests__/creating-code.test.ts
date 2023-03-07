import { Either, right } from "@sweet-monads/either";
import { of, Subject } from "rxjs";
import { CreatingCodeUseCase } from "../src/use-cases/creating-code";
import { CreatingCodeAdapters } from '../src/adapters/creating-code';

const api: CreatingCodeUseCase.Ports.Api = {
  fetch: () => {
    return of(right(true))
  }
}

test('CreatingCodeUseCase.UseCase; Success create code', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new CreatingCodeAdapters.Emiter(subject)

  const useCase = new CreatingCodeUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isRight()).toBe(true)
      subject.unsubscribe()
    },
  })

  useCase.create({
    email: 'email@gmail.com'
  })
})

test('CreatingCodeUseCase.UseCase; Invalid email', () => {
  const subject = new Subject<Either<Error, boolean>>()

  const emiter = new CreatingCodeAdapters.Emiter(subject)

  const useCase = new CreatingCodeUseCase.UseCase(
    api,
    emiter
  )

  subject.subscribe({
    next: (either: Either<Error, boolean>) => {
      expect(either.isLeft()).toBe(true)
      subject.unsubscribe()
    },
  })

  useCase.create({
    email: ''
  })
})

export {}
